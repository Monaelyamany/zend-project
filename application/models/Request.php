<?php

class Application_Model_Request  extends Zend_Db_Table_Abstract {

    protected $_name = "requests";

    function addRequest($data) {
        $row = $this->createRow();
        $row->user_id=1;
        $row->request_title = $data['request_title'];
        $row->request_text = $data['request_text'];
        return $row->save();
    }
    
     function listRequests() {
//         var_dump($this->fetchAll()->toArray());
//         exit();
         return $this->fetchAll()->toArray();
    }



}

