<?php

class Application_Form_Reply extends Zend_Form {

    public function init() {
        $request_text = new Zend_Form_Element_Text("request_text");
        $request_text->setLabel("Request Text: ")
                ->setAttrib('class', 'form-control');

        $submit = new Zend_Form_Element_Submit("submit");
        $submit->setAttrib('class', 'btn btn-info');


        $this->addElements(array($request_text, $submit));
    }

}
