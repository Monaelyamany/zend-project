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

    function getMaterialById($id) {
        return $this->find($id)->toArray();
    }

    function showHide($material) {
        if ($material[0]['is_show'] == 1) {
            $material[0]['is_show'] = "0";
            return $this->update($material[0], "material_id=" . $material[0]['material_id']);
        } else {
            $material[0]['is_show'] = "1";
            return $this->update($material[0], "material_id=" . $material[0]['material_id']);
        }
    }

    function lockDownload($material) {
        if ($material[0]['download_lock'] == 1) {
            $material[0]['download_lock'] = "0";
            return $this->update($material[0], "material_id=" . $material[0]['material_id']);
        } else {
            $material[0]['download_lock'] = "1";
            return $this->update($material[0], "material_id=" . $material[0]['material_id']);
        }
    }

    function deleteMaterial($id) {
        return $this->delete("material_id=$id");
    }

    function editMaterial($data) {
        return $this->update($data, "material_id=" . $data['material_id']);
    }

    function getMaterialByCourseId($id) {
        return $this->fetchAll($where = array('course_id = ?' => $id))->toArray();
    }

}
