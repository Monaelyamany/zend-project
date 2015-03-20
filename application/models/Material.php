<?php

class Application_Model_Material extends Zend_Db_Table_Abstract {

    protected $_name = "materials";

    function addMaterial($course_id, $data) {
        $row = $this->createRow();
        $row->material_name = $data['material_name'];
        $row->course_id = $course_id;
        return $row->save();
    }

    function listAll() {
        return $this->fetchAll()->toArray();
    }

}
