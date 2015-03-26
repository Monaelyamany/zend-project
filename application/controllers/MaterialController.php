<?php

class MaterialController extends Zend_Controller_Action {

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
        $authorization = Zend_Auth::getInstance();
        $user_info = $authorization->getStorage()->read();
        if ($user_info != NULL) {
            if ($user_info->is_admin != 1) {
                $this->redirect('user/homeuser');
            } else {
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
        }
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
        $authorization = Zend_Auth::getInstance();
        $user_info = $authorization->getStorage()->read();
        if ($user_info != NULL) {
            if ($user_info->is_admin != 1) {
                $this->redirect('user/homeuser');
            } else {
                $this->view->materials = $allmaterials;
            }
        }
    }

    function showhideAction() {
        $authorization = Zend_Auth::getInstance();
        $user_info = $authorization->getStorage()->read();
        if ($user_info != NULL) {
            if ($user_info->is_admin != 1) {
                $this->redirect('user/homeuser');
            } else {
                $id = $this->_request->getParam("material_id");
                if (!empty($id)) {
                    $material_model = new Application_Model_Material();
                    $material = $material_model->getMaterialById($id);
                    $material_model->showHide($material);
                }
                $this->redirect("material/list");
            }
        }
    }

    function lockdownloadAction() {
        $authorization = Zend_Auth::getInstance();
        $user_info = $authorization->getStorage()->read();
        if ($user_info != NULL) {
            if ($user_info->is_admin != 1) {
                $this->redirect('user/homeuser');
            } else {
                $id = $this->_request->getParam("material_id");
                if (!empty($id)) {
                    $material_model = new Application_Model_Material();
                    $material = $material_model->getMaterialById($id);
                    $material_model->lockDownload($material);
                }
                $this->redirect("material/list");
            }
        }
    }

    public function deleteAction() {
        $authorization = Zend_Auth::getInstance();
        $user_info = $authorization->getStorage()->read();
        if ($user_info != NULL) {
            if ($user_info->is_admin != 1) {
                $this->redirect('user/homeuser');
            } else {
                $id = $this->_request->getParam("material_id");
                if (!empty($id)) {
                    $material_model = new Application_Model_Material();
                    $material_model->deleteMaterial($id);
                }
                $this->redirect("material/list");
            }
        }
    }

    public function editAction() {
        $authorization = Zend_Auth::getInstance();
        $user_info = $authorization->getStorage()->read();
        if ($user_info != NULL) {
            if ($user_info->is_admin != 1) {
                $this->redirect('user/homeuser');
            } else {
                $id = $this->_request->getParam("materialid");
                $categoryname = $this->_request->getParam("categoryname");
                $coursename = $this->_request->getParam("coursename");
                $form = new Application_Form_Material();
                if ($this->_request->isPost()) {
                    $form->getElement("material_name")
                            ->removeValidator('Db_NoRecordExists');

                    if ($form->isValid($this->_request->getParams())) {
                        $material_info = $form->getValues();
                        $material_model = new Application_Model_Material();
                        $row = $material_model->editMaterial($material_info);
                        $this->redirect("material/list");
                    }
                }
                if (!empty($id)) {
                    $material_model = new Application_Model_Material();
                    $material = $material_model->getMaterialById($id);
                    $form->populate($material[0]);
                } else {
                    $this->redirect("material/list");
                }

                $this->view->category_name = $categoryname;
                $this->view->course_name = $coursename;
                $this->view->form = $form;
                $this->render('add');
            }
        }
    }

    public function downloadAction() {
        $material_id = $this->_request->getParam("material_id");
        $material_model = new Application_Model_Material();
        $array = $material_model->getMaterialById($material_id);
        $filename = $array[0]['material_name'];
        $fin = fopen("/var/www/html/zend-project/public/material/$filename", "r");
        $fout = fopen("/home/gehad/Desktop/download/$filename", "w");
        stream_filter_append($fin, "string.rot13");
        stream_filter_append($fout, "string.rot13");
        stream_copy_to_stream($fin, $fout);
        fclose($fin);
        fclose($fout);
        $this->redirect('user/homeuser');
    }

}
