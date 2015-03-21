<?php

class MaterialController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
    }

    public function indexAction() {
        // action body
    }

    public function addAction() {
        $coursename = $this->_request->getParam("course_name");
        $categoryname = $this->_request->getParam("category_name");
        $course_model = new Application_Model_Course();
        $course_id = $course_model->getCourseByName($coursename);

        $form = new Application_Form_Material();

        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getParams())) {
                $material_info = $form->getValues();
                $material_model = new Application_Model_Material();
                $material_model->addMaterial($course_id, $material_info);
                //to resend in adefault place     
                $this->redirect("material/list");
            }
        }
        $this->view->category_name = $categoryname;
        $this->view->course_name = $coursename;
        $this->view->form = $form;
    }

    public function listAction() {
        $material_model = new Application_Model_Material();
        $catcourse_model = new Application_Model_Catcourse();
        $category_model = new Application_Model_Category();
        $course_model = new Application_Model_Course();

        $allmaterials = $material_model->listAll();

        for ($i = 0; $i < count($allmaterials); $i++) {
            $course_id = $allmaterials[$i]['course_id'];
            $category_id = $catcourse_model->getCategoryId($course_id);

            $cat_data = $category_model->getCategoryById($category_id);
            $cat_name = $cat_data[0]['category_name'];

            $course_data = $course_model->getCourseById($course_id);
            $course_name = $course_data[0]['course_name'];
            $cat_course_name = array('category_name' => $cat_name, 'course_name' => $course_name);
            $allmaterials[$i]['course_id'] = $cat_course_name;
        }
        $this->view->materials = $allmaterials;
    }

    function showhideAction() {
        $id = $this->_request->getParam("material_id");
        if (!empty($id)) {
            $material_model = new Application_Model_Material();
            $material = $material_model->getMaterialById($id);
            $material_model->showHide($material);
        }
        $this->redirect("material/list");
    }

    function lockdownloadAction() {
        $id = $this->_request->getParam("material_id");
        if (!empty($id)) {
            $material_model = new Application_Model_Material();
            $material = $material_model->getMaterialById($id);
            $material_model->lockDownload($material);
        }
        $this->redirect("material/list");
    }
    
    public function deleteAction() {
        $id = $this->_request->getParam("material_id");
        if (!empty($id)) {
            $material_model = new Application_Model_Material();
            $material_model->deleteMaterial($id);
        }
        $this->redirect("material/list");
    }

}
