<?php

class Application_Form_Register extends Zend_Form
{

    public function init()
    {
        $this->setMethod("post");
        
        $id = new Zend_Form_Element_Hidden("id");
        
        $username = new Zend_Form_Element_Text("name");
        $username->setAttrib("class", "form-control");
        $username->setLabel("Username: ");
        $username->setRequired();
        $username->addFilter(new Zend_Filter_StripTags);
        
        $gender = new Zend_Form_Element_Select("gender");
        $gender->setRequired()
                ->setLabel("Gender:")
                ->addMultiOptions(array(
                    'S'=>'select',
                    'M' => 'Male',
                    'F' => 'Female'));
        
        
    
       
          
        $signature = new Zend_Form_Element_Text("signature");
        $signature->setAttrib("class", "form-control");
        $signature->setLabel("signature: ");
        $signature->setRequired();
        $signature->addFilter(new Zend_Filter_StripTags);
        
        
        $email = new Zend_Form_Element_Text("email");
        $email->setRequired()
                ->setLabel("Email:")
                 ->addValidator(new Zend_Validate_EmailAddress())
                 ->addValidator(new Zend_Validate_Db_NoRecordExists(array(
                  'table' => 'users',
                  'field' => 'user_email')));
        
        
        $country = new Zend_Form_Element_Select("countries");
        $country->setRequired()
                ->setLabel("country:")
                ->addMultiOptions(array(
                    'US' => 'united satets',
                    'UK' => 'England'  ));
        
        
        $password = new Zend_Form_Element_Password("password");
        $password->setRequired()
                 ->setLabel("Password");
        
        $photo = new Zend_Form_Element_File("file");
        $photo->setLabel("Profile picture :")
                ->setRequired();
                //->setDestination('/var/www/html/zend-project/images/users');
            

        $submit = new Zend_Form_Element_Submit("submit");
    
        
        $this->addElements(array($id,$username,$email,$gender,$password, $signature,$country,$photo,$submit));
        
 
    }


}

