<?php

class Application_Model_Comment extends Zend_Db_Table_Abstract {

    protected $_name = "comment";

    public function addComment($user_id, $materialid, $data) {
        $row = $this->createRow();
        $row->comment_text = $data;
        $row->material_id = $materialid;
        $row->user_id = $user_id;
        $row->save();
        return $this->getAdapter()->lastInsertId(); //to get last inserted id
    }

    public function getCommentByMaterialId($material_id) {
        $allcomments = $this->fetchAll($where = array('material_id = ?' => $material_id))->toArray();
        return $allcomments;
    }

    public function getCommentById($id) {
        return $this->find($id)->toArray();
    }

    public function deleteComment($id) {
        return $this->delete("comment_id=$id");
    }

    public function editComment($id, $text) {
        return $this->update(array('comment_text'=> $text), "comment_id=" . $id);
    }

}
