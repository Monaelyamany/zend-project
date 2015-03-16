<?php

class Application_Model_User extends Zend_Db_Table_Abstract
{
    protected $_name = "users";
    
    function addUser($data){
        $row = $this->createRow();
        $row->user_name = $data['name'];
        $row->gender = $data['gender'];
        $row->country = $data['countries'];
        $row->signature = $data['signature'];
        $row->user_email = $data['email'];
        $row->password = md5($data['password']);
        $row->user_photo = $data['file'];
        return $row->save();
    }

}

