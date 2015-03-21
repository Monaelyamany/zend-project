<?php

class CategoryController extends Zend_Controller_Action {

    public function init() {
        $authorization = Zend_Auth::getInstance();
        if (!$authorization->hasIdentity()) {
            $this->redirect("user/login");
        } 
    }

    public function indexAction() {
        // action body
    }

    public function addAction() {

        $form = new Application_Form_Category();

        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getParams())) {
                $category_info = $form->getValues();
                $category_model = new Application_Model_Category();
                $category_model->addCategory($category_info);
                //to resend in adefault place     
                $this->redirect("category/list");
            }
        }

        $this->view->form = $form;
    }

    public function listAction() {

        $category_model = new Application_Model_Category();
        $this->view->categories = $category_model->listCategories();
    }

    public function deleteAction() {
        $id = $this->_request->getParam("category_id");
        if (!empty($id)) {
            $category_model = new Application_Model_Category();
            $category_model->deleteCategory($id);
        }
        $this->redirect("category/list");
    }

    public function editAction() {
        $id = $this->_request->getParam("category_id");
        $form = new Application_Form_Category();
        if ($this->_request->isPost()) {
            $form->getElement("category_name")
                    ->removeValidator('Db_NoRecordExists');

            if ($form->isValid($this->_request->getParams())) {
                $category_info = $form->getValues();
                $category_model = new Application_Model_Category();
                $row = $category_model->editCategory($category_info);
                $this->redirect("category/list");
            }
        }
        if (!empty($id)) {
            $category_model = new Application_Model_Category();
            $category = $category_model->getCategoryById($id);
            $form->populate($category[0]);
        } else {
            $this->redirect("category/list");
        }



        $this->view->form = $form;
        $this->render('add');
    }

}
