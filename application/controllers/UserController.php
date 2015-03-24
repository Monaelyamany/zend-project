<?php

class UserController extends Zend_Controller_Action {

    public function init() {
        /* to call it every time when using object */
        $authorization = Zend_Auth::getInstance();
        if (!$authorization->hasIdentity() && $this->getRequest()->getActionName() != "login" && $this->getRequest()->getActionName() != "add") {
            $this->redirect("user/login");
        }
    }

    public function indexAction() {
        // action body
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
                    $storage->write($auth->getResultRowObject(array("user_id", "user_name", "user_photo", "is_admin")));

                    $login_info = $autho->getIdentity();
                    $user_model = new Application_Model_User();
                    $id = $login_info->user_id;
                    $user_info = $user_model->getUserById($id);
                    if ($user_info[0]['is_admin'] == 1) {
                        $this->redirect("user/homeadmin");
                    } else {
                        $this->redirect('user/homeuser');
                    }
                } else {
                    $this->view->message = "not valid user ";
                }
            }
        }
    }

    public function logoutAction() {
        $autho = Zend_Auth::getInstance();
        $autho->clearIdentity();
        $this->redirect('/user/login');
    }

    public function homeadminAction() {
        $this->view->message = 'homeadmin';
    }

    public function homeuserAction() {
        $msg = $this->_request->getParam("msg");
        if ($msg == "category") {
            $category_model = new Application_Model_Category();
            $allcategory = $category_model->listCategories();
            $this->view->msg = $msg;
            $this->view->allcategories = $allcategory; //all categories
            $cat_id = $this->_request->getParam('cat_id');
            if ($cat_id) {
                $catcourse_model = new Application_Model_Catcourse();
                $course_model = new Application_Model_Course();
                $courses_id = $catcourse_model->getCourseId($cat_id);
                $allcourses = array(); //all courses related to certain category
                for ($i = 0; $i < count($courses_id); $i++) {
                    $course_id = $courses_id[$i]['course_id'];
                    $course_name = $course_model->getCourseById($course_id);
                    array_push($allcourses, $course_name);
                }
                $this->view->selectedcat = $cat_id;
                $this->view->course = "show";
                $this->view->allcourses = $allcourses;
            }
            $course_id = $this->_request->getParam('course_id');
            if ($course_id) {
                $material_model = new Application_Model_Material();
                $allmaterial = $material_model->getMaterialByCourseId($course_id);
                $this->view->selectedcourse = $course_id;
                $this->view->allmaterials = $allmaterial;
                $this->view->material = "show";
            }
        }
    }

    public function materialAction() {
        $material_id = $this->_request->getParam('material_id');
        if ($material_id) {
            $material_model = new Application_Model_Material();
            $material_data = $material_model->getMaterialById($material_id);
            $comment_model=new Application_Model_Comment();
            $comments=$comment_model->getCommentByMaterialId($material_id);
            for($i=0;$i<count($comments);$i++){
                $user_id=$comments[$i]['user_id'];
                $user_model=new Application_Model_User();
                $user_data=$user_model->getUserById($user_id);
                $user=array();
                echo '<br>'; echo '<br>'; echo '<br>'; echo '<br>';
                array_push($user, $user_data[0]['user_name']);
                array_push($user, $user_data[0]['user_photo']);
                $comments[$i]['user_id']=$user;
                echo '<br>';
            }
            
            
            $this->view->material_data = $material_data;
            $this->view->comments=$comments;
        }
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
                    echo "Error";
                }

                // create object from zend_mail class
                $myemail = new Zend_Mail();
                // body of email
                $myemail->setBodyText("Hi $name \n Your email is:$email \n Your gender is:$gender \n Your signature is:$signature \n Your country is:$country ")
                        ->setFrom('grm3zend@gmail.com')//the sender
                        ->addTo($email)//the receiver
                        ->setSubject('Greetings and Salutations!')//subject
                        ->send(); //function to send email 
                $this->redirect("user/list");
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

    public function adminuserAction() {
        $id = $this->_request->getParam("user_id");
        if (!empty($id)) {
            $user_model = new Application_Model_User();
            $user = $user_model->getUserById($id);
            $user_model->adminUser($user);
        }
        $this->redirect("user/list");
    }

    public function banunbanAction() {
        $id = $this->_request->getParam("user_id");
        if (!empty($id)) {
            $user_model = new Application_Model_User();
            $user = $user_model->getUserById($id);
            $user_model->banUnban($user);
        }
        $this->redirect("user/list");
    }

}
