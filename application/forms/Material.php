<?php

class Application_Form_Material extends Zend_Form {

    public function init() {
        $id=new Zend_Form_Element_Hidden('matreial_id');
        $materialname = new Zend_Form_Element_File('material_name');
        $materialname->setLabel('Upload an image:')
                ->setRequired()
                ->setDestination('/var/www/html/zend-project/material/');
                


        $submit = new Zend_Form_Element_Submit("submit");
        $submit->setAttrib('class', 'btn btn-primary');


        $this->addElements(array($id,$materialname,$submit));
    }

}
