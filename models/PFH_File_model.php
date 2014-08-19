<?php
/**
 * PFH_File_model
 * 
 * 檔案
 *
 * @author Pulipuli Chen <pulipuli.chen@gmail.com>
 * @version 20140819
 */
class PFH_File_model {
    
    /**
     * 取得檔案
     * @param Int|String $id
     * @return RedBean
     */
    static function get($id) {
        if (is_string($id)) {
            $id = Base56::decode($id);
        }
        
        $bean = R::load( 'file', $id );
        return $bean;
    }

    static function create_from_upload($f3, $upload_file, $md5 = NULL) {
                
        // 檔案大小
        $filesize = $upload_file['size'];
                
        // 檔案類型
        $filetype = $upload_file['type'];
        
        // md5
        if (is_null($md5)) {
            $md5 = md5_file($upload_file["tmp_name"]);
        }
        
        // 搜尋看看有沒有這個bean
        $file = R::findOne("file", "md5 = ?", [$md5]);
        
        if (is_null($file)) {
        
            $file = R::dispense("file");
            //$file->title="哈利波特";
            //$id = R::store($file);
            //$data = array();

            // 檔案名稱
            $filename = $upload_file['name'];
            //$data["filename"] = $filename;
            $file->filename = $filename;

            // 檔案大小
            $file->filesize = $filesize;

            // 檔案類型
            $file->filetype = $filetype;

            // md5
            $file->md5 = $md5;

            // 是否刪除
            $deleted = FALSE;
            //$data["deleted"] = $deleted;
            $file->deleted = $deleted;

            //$hash = Base56::encode(1000000);
            //$data["hash"] = $hash;

            $action = "upload";
            PFH_Log_model::create_log($f3, $file, $action);

            R::store($file);
            //$bean->id = 9;
            
        }   //if (is_null($file)) {
        
        //echo $file->id;
        
        return $file;
    }
    
    /**
     * 從MD5取得檔案路徑
     * 
     * @param Object $f3
     * @param String $md5
     * @return string
     * @author Pulipuli Chen <pulipuli.chen@gmail.com>
     * @version 20140819
     */
    static function get_file_path_from_md5($f3, $md5) {
        $path1 = substr($md5, 0, 2);
        $path2 = substr($md5, 2, 2);
        $file_name = substr($md5, 4);
        
        
        $file_dir = $f3->get("UPLOADS") 
                . $path1 . "/"
                . $path2 . "/";
        
        if (is_dir($file_dir) === FALSE) {
            mkdir($file_dir, 0700, true);
        }
        $file_path = $file_dir . $file_name;
        
        return $file_path;
    }
    
    /**
     * 
     * @param Object $f3
     * @param RedBean $bean
     * @return string 連結
     */
    static function get_link($f3, $bean) {
        
        //$link = "/get/1000/檔案名稱.txt";
        
        //$id = $bean->id;
        $id = 4;
        $hash_id = Base56::encode($id);
        
        $filename = $bean->filename;
        $filename = urlencode($filename);
        
        $link = "/get/" . $hash_id . "/" . $filename;
        
        $link = PFH_URL_helper::get_base_url($f3, $link);
        
        return $link;
    }
    
}
