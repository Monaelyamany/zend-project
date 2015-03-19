<?php

class Application_Form_Course extends Zend_Form {

    public function init() {
        $id = new Zend_Form_Element_Hidden("course_id");

        $coursename = new Zend_Form_Element_Text("course_name");
        $coursename->setLabel("Course Name: ")
                ->addValidator(new Zend_Validate_Db_NoRecordExists(array(
                    'table' => 'courses',
                    'field' => 'course_name')))
                ->setAttrib('class', 'form-control');

        $coursename->setRequired();
        $coursename->addFilter(new Zend_Filter_StripTags);
        $submit = new Zend_Form_Element_Submit("submit");
        $submit->setAttrib('class', 'btn btn-info');


        $this->addElements(array($id, $coursename, $submit));
    }

}
