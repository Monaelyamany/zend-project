<?php

class CommentController extends Zend_Controller_Action {

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

        $form = new Application_Form_Comment();
        $text = $this->_request->getParam("comment_text");
        $mateial_id = $this->_request->getParam("materialid");
        $authorization = Zend_Auth::getInstance();
        $user_info = $authorization->getStorage()->read();
        if ($user_info != NULL) {
            $user_id = $user_info->user_id;
        }
        if (!empty($text)) {
            $comment_model = new Application_Model_Comment();
            $comment_id = $comment_model->addComment($user_id, $mateial_id, $text);
            echo $comment_id;
            exit();
        }
    }

    public function selectAction() {
        $comment_id = $this->_request->getParam("comment_id");
        $comment_model = new Application_Model_Comment();
        $comment_data = $comment_model->getCommentById($comment_id);
        $user_id = $comment_data[0]['user_id'];
        $user_model = new Application_Model_User();
        $user_data = $user_model->getUserById($user_id);
        $user = array();
        array_push($user, $user_data[0]['user_name']);
        array_push($user, $user_data[0]['user_photo']);
        $comment_data[0]['user_id'] = $user;
        echo json_encode($user);
        exit();
    }

    public function deleteAction() {
        $comment_id = $this->_request->getParam("comment_id");
        if (!empty($comment_id)) {
            $comment_model = new Application_Model_Comment();
            $comment_model->deleteComment($comment_id);
            exit();
        }
    }

    public function editAction() {
        $comment_id = $this->_request->getParam("comment_id");
        $comment_text = $this->_request->getParam("comment_text");
        if (!empty($comment_id)) {
            $comment_model = new Application_Model_Comment();
            $comment_model->updateComment($comment_id, $comment_text);
        }
    }

}
