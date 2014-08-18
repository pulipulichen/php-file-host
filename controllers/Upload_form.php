<?php
/**
 * Upload_form
 * 
 * 檔案上傳
 *
 * @author pudding
 */
class Upload_form {
    
    /**
     * 顯示上傳檔案的位置
     * @version 20140818 Pulipuli Chen
     */
    function display_view() {
        
        //$file = R::dispense("file");
        //$file->title="哈利波特";
        //$id = R::store($file);
        
        $template = new Template;
        echo $template->render("upload_form.html");
        
        //echo "upload form " . $id;
    }
}
