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
        echo "get file ". $f3->get('PARAMS.hash');
    }
}
