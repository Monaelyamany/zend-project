<?php

class Application_Form_Comment extends Zend_Form {

    public function init() {
        $id = new Zend_Form_Element_Hidden("comment_id");

        $commenttext = new Zend_Form_Element_Text("comment_text");
        $commenttext->setLabel("Comment: ")
                ->setAttrib('class', 'form-control')
                ->setRequired()
                ->addFilter(new Zend_Filter_StripTags);
        $submit = new Zend_Form_Element_Submit("send");
        $submit->setAttrib('class', 'btn btn-info');


        $this->addElements(array($id, $commenttext, $submit));
    }

}
