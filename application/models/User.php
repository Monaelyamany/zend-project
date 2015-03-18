<?php

class Application_Model_User extends Zend_Db_Table_Abstract {

    protected $_name = "users";

    function addUser($data) {
        $row = $this->createRow();
        $row->user_name = $data['user_name'];
        $row->gender = $data['gender'];
        $row->country = $data['country'];
        $row->signature = $data['signature'];
        $row->user_email = $data['user_email'];
        $row->password = md5($data['password']);
        $row->user_photo = $data['user_photo'];
        return $row->save();
    }

    function listUsers() {
        return $this->fetchAll()->toArray();
    }

    function deleteUser($id) {
        return $this->delete("user_id=$id");
    }

    function getUserById($id){
        return $this->find($id)->toArray();
    }
            
    function editUser($data){
        if(!empty($data['password']))
            $data['password']=md5($data['password']);
        else
            unset ($data['password']);
        return $this->update($data, "user_id=".$data['user_id']);
         
    }
}
