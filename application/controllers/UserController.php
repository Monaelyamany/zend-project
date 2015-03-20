<?php

class UserController extends Zend_Controller_Action {

    public function init() {
        /* to call it every time when using object */
        $authorization = Zend_Auth::getInstance();
        if (!$authorization->hasIdentity() && $this->_request->getActionName() != 'login') {
            //$this->redirect("user/login"); 
        }
    }

    public function indexAction() {
        // action body
    }

    public function addAction() {

        $form = new Application_Form_Register();

        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getParams())) {
                $user_info = $form->getValues();
                $email = $form->getValue("user_email");
                $name = $form->getValue("user_name");
                $country = $form->getValue("country");
                $gender = $form->getValue("gender");
                $signature = $form->getValue("signature");
                $user_model = new Application_Model_User();
                $user_model->addUser($user_info);
                //to resend in adefault place     
//               $tr = new Zend_Mail_Transport_Sendmail('-freturn_to_grm3zend@example.com');
//                Zend_Mail::setDefaultTransport($tr);
//                // create object from zend_mail class
//                    $myemail=new Zend_Mail();
//                    // body of email
//                    $myemail->setBodyText("hi $name \n your email is:$email \n your gender is:$gender \n your signature is:$signature \n your country is:$country ")
//                            ->setFrom('grm3zend@gmail.com')//the sender
//                            ->addTo($email)//the receiver
//                            ->setSubject('Greetings and Salutations!')//subject
//                            ->send();//function to send email 
                $this->redirect("user/list");
            }
        }

        $this->view->form = $form;
    }

    public function loginAction() {
        // remove element from user form
        $userForm = new Application_Form_Register();
        $userForm->removeElement("user_name");
        $userForm->removeElement("user_id");
        $userForm->removeElement("gender");
        $userForm->removeElement("signature");
        $userForm->removeElement("country");
        $userForm->removeElement("user_photo");
        $userForm->getElement("user_email")->removeValidator('Db_NoRecordExists');
        $this->view->form = $userForm;
        //check if form is valid
        if ($this->_request->isPost()) {
            if ($userForm->isValid($this->getRequest()->getParams())) {
                //get values of email password 
                $email = $userForm->getValue("user_email");
                $password = $userForm->getValue("password");


                //check if user is authenticated or not
                $db = Zend_Db_Table::getDefaultAdapter();
                $auth = new Zend_Auth_Adapter_DbTable($db, 'users', "user_email", "password");
                $auth->setIdentity($email);
                $auth->setCredential(md5($password));
                $row = $auth->authenticate();
                if ($row->isValid()) {
                    //get data from database and store it in session
                    $autho = Zend_Auth::getInstance();
                    $storage = $autho->getStorage();
                    $storage->write($auth->getResultRowObject(array("user_id", "user_name", "user_photo")));

                    $this->view->message = "valid user ";
                } else {
                    $this->view->message = "not valid user ";
                }
            }
        }
    }

    public function listAction() {

        $user_model = new Application_Model_User();
        $this->view->users = $user_model->listUsers();
    }

    public function deleteAction() {
        $id = $this->_request->getParam("user_id");
        if (!empty($id)) {
            $user_model = new Application_Model_User();
            $user_model->deleteUser($id);
        }
        $this->redirect("user/list");
    }

    public function editAction() {
        $id = $this->_request->getParam("user_id");
        $form = new Application_Form_Register();
        if ($this->_request->isPost()) {
            $form->getElement("user_email")->removeValidator("Zend_Validate_Db_NoRecordExists");
            $form->getElement("password")->setRequired($flag = FALSE);
            if ($form->isValid($this->_request->getParams())) {
                $user_info = $form->getValues();
                $user_model = new Application_Model_User();
                $row = $user_model->editUser($user_info);
                $this->redirect("user/list");
            }
        }
        if (!empty($id)) {
            $form->getElement("user_email")->setAttrib('readonly', 'readonly');
            $user_model = new Application_Model_User();
            $user = $user_model->getUserById($id);
            $form->populate($user[0]);
        } else
            $this->redirect("user/list");



        $form->getElement("password")->setRequired(false);
        $this->view->form = $form;
        $this->render('add');
    }

    function adminuserAction() {
        $id = $this->_request->getParam("user_id");
        if (!empty($id)) {
            $user_model = new Application_Model_User();
            $user = $user_model->getUserById($id);
            $user_model->adminUser($user);
        }
        $this->redirect("user/list");
    }

    function banunbanAction() {
        $id = $this->_request->getParam("user_id");
        if (!empty($id)) {
            $user_model = new Application_Model_User();
            $user = $user_model->getUserById($id);
            $user_model->banUnban($user);
        }
        $this->redirect("user/list");
    }

}
