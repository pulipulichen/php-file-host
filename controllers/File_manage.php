<?php
/**
 * File_manage
 * 
 * 檔案下載與刪除
 *
 * @author pudding
 */
class File_manage {
    function get_file($f3) {
        $hash_id = $f3->get("PARAMS.hash_id");
        $id = Base56::decode($hash_id);
        echo $id;
    }
}
