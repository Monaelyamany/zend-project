<?php

class Application_Model_Catcourse extends Zend_Db_Table_Abstract {

    protected $_name = "category_course";

    function addData($catid, $courseid) {
        $row = $this->createRow();
        $row->category_id = $catid;
        $row->course_id = $courseid;
        return $row->save();
    }

    function listAll() {
        return $this->fetchAll()->toArray();
    }

    function getCategoryId($id) {
        $category_id = $this->fetchAll($where = array('course_id = ?' => $id))->toArray();
        return $category_id[0]['category_id'];
    }

}
