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
//                $this->redirect("material/list");
            }
        }
        $this->view->category_name = $categoryname;
        $this->view->course_name = $coursename;
        $this->view->form = $form;
    }

    public function listAction() {
        $material_model = new Application_Model_Material();
        $allmaterials = $material_model->listAll();
        $this->view->materials = $allmaterials;
    }

}
