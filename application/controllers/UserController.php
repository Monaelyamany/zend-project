<?php

class UserController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
    }

    public function indexAction() {
        // action body
    }

    public function addAction() {
        $form = new Application_Form_Register();

        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getParams())) {
                $user_info = $form->getValues();
                $user_model = new Application_Model_User();
                $user_model->addUser($user_info);
            }
        }

        $this->view->form = $form;
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
            }
        }
        if (!empty($id)) {
            $form->getElement("user_email")->setAttrib('readonly','readonly');
            $user_model = new Application_Model_User();
            $user = $user_model->getUserById($id);     
            $form->populate($user[0]);
           
        } else
            $this->redirect("user/list");



        $form->getElement("password")->setRequired(false);
        $this->view->form = $form;
        $this->render('add');
    }

}
