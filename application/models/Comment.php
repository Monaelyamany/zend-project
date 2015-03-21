<?php

class Application_Model_Comment {

    protected $_name = "comment";

    function addComment($data) {
        $row = $this->createRow();
        $row->comment_text = $data['comment_text'];
        return $row->save();
    }

}
