<?php

class UserController extends Zend_Controller_Action
{

    public function init()
    {
        /* to call it every time when using object */
        $authorization =Zend_Auth::getInstance();
        if(!$authorization->hasIdentity() && $this->_request->getActionName()!='login'){
            //$this->redirect("user/login");
    
         }
    }

    public function indexAction()
    {
        // action body
    }

    
    public function addAction()
    {
       
       $form  = new Application_Form_Register();
       
       if($this->_request->isPost()){
           if($form->isValid($this->_request->getParams())){
               $user_info = $form->getValues();
               $email=$form->getValue("email");
               $name=$form->getValue("name");
               $country=$form->getValue("countries");
               
               echo $email;
               $user_model = new Application_Model_User();
               $user_model->addUser($user_info);
               //to resend in adefault place
                  
               $tr = new Zend_Mail_Transport_Sendmail('-freturn_to_grm3zend@example.com');
                Zend_Mail::setDefaultTransport($tr);
                // create object from zend_mail class
                    $myemail=new Zend_Mail();
                    // body of email
                    $myemail->setBodyText("hi $name  your email is:$email and your country is:$country ")
                            ->setFrom('grm3zend@gmail.com')//the sender
                            ->addTo($email)//the receiver
                            ->setSubject('Greetings and Salutations!')//subject
                            ->send();//function to send email

                   
           }
       }
       
	$this->view->form = $form;

    }
    public function loginAction()
    {
        // remove element from user form
        $userForm = new Application_Form_Register();
	$userForm->removeElement("name");
	$userForm->removeElement("id");
        $userForm->removeElement("gender");
	$userForm->removeElement("signature");
        $userForm->removeElement("countries");
        $userForm->removeElement("file");
        $userForm->getElement("email")->removeValidator('Db_NoRecordExists');
        $this->view->form = $userForm;
        //check if form is valid
        if($this->_request->isPost()){
            if($userForm->isValid($this->getRequest()->getParams())){
               //get values of email password 
                $email = $userForm->getValue("email");
                $password = $userForm->getValue("password");
                
                
                //check if user is authenticated or not
                $db=  Zend_Db_Table::getDefaultAdapter();
                $auth = new Zend_Auth_Adapter_DbTable($db, 'users', "user_email" , "password");
                $auth->setIdentity($email);
                $auth->setCredential(md5($password));
                $row  = $auth->authenticate();
                if($row->isValid()){
                    //get data from database and store it in session
                    $autho = Zend_Auth::getInstance();
                    $storage = $autho->getStorage();
                    $storage->write($auth->getResultRowObject(array("user_id","user_name","user_photo")));
                    
                    $this->view->message = "valid user ";
                }  else {
                    $this->view->message = "not valid user ";
                }
            }
        }

    }

}

