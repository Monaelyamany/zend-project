<?php

class Application_Model_Request extends Zend_Db_Table_Abstract {

    protected $_name = "requests";

    function addRequest($user_id,$data) {
        $row = $this->createRow();
        $row->user_id = $user_id;   // get this info from session 
        $row->request_title = $data['request_title'];
        $row->request_text = $data['request_text'];
        return $row->save();
    }

    function listRequests() {
        $select = $this->select()
                ->order('request_date DESC');
        return $this->fetchAll($select)->toArray();
    }

    function getRequestById($id) {
        return $this->find($id)->toArray();
    }

    function deleteRequest($id) {
        return $this->delete("request_id=$id");
    }

}
