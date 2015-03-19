<?php

class Application_Form_Category extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
        $id = new Zend_Form_Element_Hidden("category_id");
        
        $categoryname = new Zend_Form_Element_Text("category_name");
        $categoryname->setLabel("Category Name: ")
                ->addValidator(new Zend_Validate_Db_NoRecordExists(array(
                    'table' => 'category',
                    'field' => 'category_name')));

        $categoryname->setRequired();
        $categoryname->addFilter(new Zend_Filter_StripTags);
        $submit = new Zend_Form_Element_Submit("submit");
    
        
        $this->addElements(array($id,$categoryname,$submit));
        
      
    }


}

