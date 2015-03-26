<?php

class RequestController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
        $authorization = Zend_Auth::getInstance();
        if (!$authorization->hasIdentity()) {
            $this->redirect("user/login");
        }
    }

    public function indexAction() {
        // action body
    }

    public function addAction() {

        $form = new Application_Form_Request();

        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getParams())) {
                $request_info = $form->getValues();
                $authorization = Zend_Auth::getInstance();
                $user_info = $authorization->getStorage()->read();
                $user_id = $user_info->user_id;
                $title = $form->getValue("request_title");
                $text = $form->getValue("request_text");
                $request_model = new Application_Model_Request();
                $request_model->addRequest($user_id, $request_info);
                //to resend in adefault place     

                $this->redirect("request/add");
            }
        }

        $this->view->form = $form;
    }

    public function listAction() {
        $authorization = Zend_Auth::getInstance();
        $user_info = $authorization->getStorage()->read();
        if ($user_info != NULL) {
            if ($user_info->is_admin != 1) {
                $this->redirect('user/homeuser');
            } else {

                $request_model = new Application_Model_Request();
                $request_data = $request_model->listRequests();

                for ($i = 0; $i < count($request_data); $i++) {
                    $user_id = $request_data[$i]['user_id'];


                    $user_data = new Application_Model_User();
                    $user = $user_data->getUserById($user_id);
                    for ($j = 0; $j < count($user); $j++) {
                        $user_name = $user[$j]['user_name'];
                        $user_photo = $user[$j]['user_photo'];
                        $user_array = array($user_name, $user_photo);
                        $request_data[$i]['user_id'] = $user_array;
                    }
                    $requests = array($request_data, $user_array);
                    $this->view->requests = $request_data;
                }
            }
        }
    }

    public function sendemailAction() {
        $requestid = $this->_request->getParam("requestid");
        $requesttext = $this->_request->getParam("requesttext");
        var_dump($requesttext);
        if (!empty($requestid)) {
            $request_detail = new Application_Model_Request();
            $data = $request_detail->getRequestById($requestid);
            $user_id = $data[0]['user_id'];

            $user = new Application_Model_User();
            $user_data = $user->getUserById($user_id);
            $user_email = $user_data[0]['user_email'];
            $user_name = $user_data[0]['user_name'];

            // send email when click in button reply

            try {
                $config = array(
                    'auth' => 'login',
                    'username' => 'grm3zend@gmail.com',
                    'password' => 'grm1234567',
                    'ssl' => 'tls',
                    'port' => 587
                );

                $mailTransport = new Zend_Mail_Transport_Smtp('smtp.gmail.com', $config);
                Zend_Mail::setDefaultTransport($mailTransport);
            } catch (Zend_Exception $e) {
                //Do something with exception
            }

            // create object from zend_mail class
            $myemail = new Zend_Mail();
            // body of email
            $myemail->setBodyText("Hi $user_name \n Thanks for visiting our website \n $requesttext")
                    ->setFrom('grm3zend@gmail.com')//the sender
                    ->addTo($user_email)//the receiver
                    ->setSubject('Reply to your Requests!')//subject
                    ->send(); //function to send email

            $request_detail->deleteRequest($requestid);
            $this->redirect("request/list");
//   
        }
    }

    public function replyAction() {
        $requestid = $this->_request->getParam("request_id");
        $username = $this->_request->getParam("user_name");

        $form = new Application_Form_Reply();

        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getParams())) {
                $request_text = $form->getValue("request_text");
                $this->redirect("request/sendemail/requestid/$requestid/requesttext/$request_text");
            }
        }
        $this->view->username = $username;
        $this->view->form = $form;
    }

}
