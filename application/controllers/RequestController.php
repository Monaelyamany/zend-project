<?php

class RequestController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
    }

    public function indexAction() {
        // action body
    }

    public function addAction() {

        $form = new Application_Form_Request();

        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getParams())) {
                $request_info = $form->getValues();
                $title = $form->getValue("request_title");
                $text = $form->getValue("request_text");


                $request_model = new Application_Model_Request();
                $request_model->addRequest($request_info);
                //to resend in adefault place     

                $this->redirect("request/add");
            }
        }

        $this->view->form = $form;
    }

    public function listAction() {

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
        }

//        $requests = array($request_data, $user_array);
//        var_dump($requests);
//        exit();
        
        $request_data[$i]['user_id'] = $user_array;
//        var_dump($request_data);
//        exit();
        $this->view->requests = $request_data;
    }
  }

}
