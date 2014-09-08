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
    
    /**
     * 取得檔案
     * @param String $id
     * @return RedBean
     */
    static function get_by_hash_id($id) {
        $id = Base56::decode($id);
        $bean = R::load( 'file', $id );
        return $bean;
    }
    
    /**
     * 取得檔案
     * @param Int $id
     * @return RedBean
     */
    static function get_by_id($id) {
        $id = intval($id);
        $bean = R::load( 'file', $id );
        return $bean;
    }

    static function create_from_upload($f3, $upload_file, $md5 = NULL) {
        
        // 檔案名稱
        $filename = $upload_file['name'];
        
        // 檔案大小
        $filesize = $upload_file['size'];
                
        // 檔案類型
        $filetype = $upload_file['type'];
        
        // md5
        if (is_null($md5)) {
            $md5 = md5_file($upload_file["tmp_name"]);
            
            $same_md5 = R::findOne("file", "md5 = ? AND (filesize <> ? OR filetype <> ?)", [$md5, $filesize, $filetype]);
            if (is_null($same_md5) === FALSE) {
                $same_md5_id = $same_md5->id;
                $hash_id = Base56::encode($same_md5_id);
                $md5 = $md5 . $hash_id;
            }
        }
        
        // 搜尋看看有沒有這個bean
        $file = R::findOne("file", "md5 = ? AND filename = ? AND filesize = ? AND filetype = ?", [$md5, $filename, $filesize, $filetype]);
        
        if (is_null($file)) {
        
            $file = R::dispense("file");
            //$file->title="哈利波特";
            //$id = R::store($file);
            //$data = array();

            // 檔案名稱
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

            $id = R::store($file);
            $file->id = $id;
            //$bean->id = 9;
            
            // 移動檔案
            self::move_uploaded_file($f3, $upload_file["tmp_name"], $md5);
            
            // 變成壓縮檔案
            PFH_ZIP::create($f3, $md5);
            
        }   //if (is_null($file)) {
        
        // 記錄上傳的資料
        $action = "upload";
        PFH_Log_model::create_log($f3, $file, $action);

        //echo $file->id;
        
        return $file;
    }
    
    /**
     * 移動檔案
     * 
     * @param Object $f3
     * @param String $from_filepath 來源檔案
     * @param String $md5
     */
    static private function move_uploaded_file($f3, $from_filepath, $md5) {
        $target_file_path = PFH_MD5::get_file_path($f3, $md5);
        
        if (is_file($target_file_path) === FALSE) {
            move_uploaded_file($from_filepath,
                 $target_file_path);
            
            if (is_file($target_file_path) === FALSE) {
                rename($from_filepath, $target_file_path);
            }
        }
        
        if (is_file($from_filepath)) {
            unlink($from_filepath);
        }
    }
    
    /**
     * 
     * @param Object $f3
     * @param RedBean $bean
     * @return string 連結
     */
    static function get_link($f3, $bean) {
        
        //$link = "/get/1000/檔案名稱.txt";
        
        $id = $bean->id;
        //$id = 4;
        $hash_id = Base56::encode($id);
        
        $filename = $bean->filename;
        $filename = urlencode($filename);
        
        $link = "/get/" . $hash_id . "/" . $filename;
        
        $link = PFH_URL_helper::get_base_url($f3, $link);
        
        return $link;
    }
    
}
