<?php

class Application_Model_Category extends Zend_Db_Table_Abstract
{
    protected $_name = "category";

    function addCategory($data) {
        $row = $this->createRow();
        $row->category_name = $data['category_name'];
        return $row->save();
    }
    function listCategories() {
        return $this->fetchAll()->toArray();
    }
    function deleteCategory($id) {
        return $this->delete("category_id=$id");
    }

    function getCategoryById($id){
        return $this->find($id)->toArray();
    }
            
    function editCategory($data){
        return $this->update($data, "category_id=".$data['category_id']);     
    }
    


}

