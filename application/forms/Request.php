<?php

class Application_Form_Request extends Zend_Form
{

    public function init()
    {
        $request_id = new Zend_Form_Element_Hidden("request_id");
        
        $request_title = new Zend_Form_Element_Text("request_title");
        $request_title->setLabel("Course Name: ")
                ->addValidator(new Zend_Validate_Db_NoRecordExists(array(
                    'table' => 'requests',
                    'field' => 'request_title')))
                ->setAttrib('class', 'form-control');
        
        
        $request_text = new Zend_Form_Element_Text("request_text");
        $request_text->setLabel("Request: ")
                ->addValidator(new Zend_Validate_Db_NoRecordExists(array(
                    'table' => 'requests',
                    'field' => 'request_text')))
                ->setAttrib('class', 'form-control');
        
        $submit = new Zend_Form_Element_Submit("submit");
        $submit->setAttrib('class','btn btn-info');
    
        
        $this->addElements(array($request_id,$request_title,$request_text,$submit));
          /* Form Elements & Other Definitions Here ... */
    }


}

