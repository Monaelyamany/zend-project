<?php

class CourseController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
    }

    public function indexAction() {
        // action body
    }

    public function addAction() {
        $id = $this->_request->getParam("category_id");
        $category_model = new Application_Model_Category();
        $category_data = $category_model->getCategoryById($id);
        $form = new Application_Form_Course();
        if (!empty($id)) {
            if ($this->_request->isPost()) {
                if ($form->isValid($this->_request->getParams())) {
                    $course_info = $form->getValues();
                    $course_model = new Application_Model_Course();
                    $course_model->addCourse($course_info);
                    $course_id = $course_model->getLastCourseId();

                    $catcourse_model = new Application_Model_Catcourse();
                    $catcourse_model->addData($id, $course_id);

                    //to resend in adefault place     
                    $this->redirect("course/list");
                }
            }
        }
        $this->view->category_name = $category_data[0]['category_name'];
        $this->view->form = $form;
    }

    public function listAction() {
        $catcourse_model = new Application_Model_Catcourse();
        $course_model = new Application_Model_Course();
        $category_model = new Application_Model_Category();
        $allcatcourse = $catcourse_model->listAll();

        for ($i = 0; $i < count($allcatcourse); $i++) {
            $cat_id = $allcatcourse[$i]['category_id'];
            $cat_data = $category_model->getCategoryById($cat_id);
            $allcatcourse[$i]['category_id'] = $cat_data[0]['category_name'];


            $course_id = $allcatcourse[$i]['course_id'];
            $course_data = $course_model->getCourseById($course_id);
            $allcatcourse[$i]['course_id'] = $course_data[0]['course_name'];
        }
        $this->view->courses = $allcatcourse;
    }

    public function deleteAction() {
        $coursename = $this->_request->getParam("course_name");  
        if (!empty($coursename)) {
            $course_model=new Application_Model_Course();
            $course_id=$course_model->getCourseByName($coursename);
            
            $course_model->deleteCourse($course_id);
        }
        $this->redirect("course/list");
    }

}
