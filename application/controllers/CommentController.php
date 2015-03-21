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

        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getParams())) {
                $comment_info = $form->getValues();
                $comment_model = new Application_Model_Comment();
                $comment_model->addComment($comment_info);
                //to resend in adefault place     
                $this->redirect("comment/list");
            }
        }

        $this->view->form = $form;
    }

}
