<?php

class Application_Model_Course extends Zend_Db_Table_Abstract {

    protected $_name = "courses";

    function addCourse($data) {
        $row = $this->createRow();
        $row->course_name = $data['course_name'];
        return $row->save();
    }

    function getLastCourseId() {
        return $this->getAdapter()->lastInsertId();
    }

    function listCourses() {
        return $this->fetchAll()->toArray();
    }

    function getCourseById($id) {
        return $this->find($id)->toArray();
    }
    
    
     function editCourse($data){
        return $this->update($data, "course_id=".$data['course_id']);
         
    }

}
