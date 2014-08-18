<?php
/**
 * Upload_form
 * 
 * 檔案上傳
 *
 * @author pudding
 */
class Upload_form {
    function display_view() {
        
        $file = R::dispense("file");
        $file->title="哈利波特";
        $id = R::store($file);
        
        echo "upload form " . $id;
    }
}
