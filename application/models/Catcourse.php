<?php

class Application_Model_Catcourse extends Zend_Db_Table_Abstract
{
    protected $_name = "category_course";
    
    function addData($catid,$courseid) {
        $row = $this->createRow();
        $row->category_id= $catid;
        $row->course_id = $courseid;
        return $row->save();
    }
    
    function listAll() {
        return $this->fetchAll()->toArray();
    }

}

