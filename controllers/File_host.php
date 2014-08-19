<?php
/**
 * File_host
 * 
 * 檔案上傳
 *
 * @author Pulipuli Chen <pulipuli.chen@gmail.com>
 * @version 20140819
 */
class File_host {
    
    private $session_key = "SESSION.upload_file_id";

    /**
     * 上傳檔案
     * @param Object $f3
     * @return boolean
     */
    public function upload($f3) {
        
        if (isset($_FILES["file"]) === FALSE) {
            $f3->reroute("/");
            return $this;
        }
        
        $file = $_FILES["file"];
        
        //var_dump($file);
        
        $validate_result = $this->_validate_file($f3, $file);
        
        $result = FALSE;
        
        
        // 召喚session
        /*
        $db = $f3->get("DATABASE.host");
        if (String_helper::starts_with($db, "sqlite:") === TRUE 
                && String_helper::starts_with($db, "sqlite:/") === FALSE) {
            $filename = substr($db, strpos($db, ":")+1);
            //echo $filename;
            $db = "sqlite://" . PFH_File_helper::get_root_dir($f3, $filename);
            //sqlite://D:\xampp\htdocs\php-file-host\phpfilehost.sqlite.db
            $db = "sqlite:phpfilehost.sqlite.db";
            echo $db;
        }            
        */
        if ($validate_result === TRUE) {
            $tmp_path = $_FILES["file"]["tmp_name"];
            $md5 = md5_file($tmp_path);
            $file_path = PFH_File::get_file_path_from_md5($f3, $md5);

            if (is_file($file_path) === FALSE) {
                move_uploaded_file($tmp_path,
                     $file_path);
            }
          
            //$result = $this->_db_record_create($f3, $file, $md5);
            $bean = PFH_File::create_from_upload($f3, $file);
            // 新增KEY到SESSION之中
            $f3->set($this->session_key, $bean->id);
        }
        else {
            // 從SESSION刪除
            $f3->clear($this->session_key);
        }
        
        // -------------------------
        // reroute
        
        $reroute = "/upload";
        if ($f3->exists("GET.callback")) {
            $reroute = $reroute . "?callback=" . $f3->get("GET.callback");
        }
        
        $f3->reroute($reroute);
        return $this;
    }
    
    public function get_link($f3) {
        
        if ($f3->exists($this->session_key) === FALSE) {
            $f3->reroute("/");
            return $this;
        }
        
        $id = $f3->get($this->session_key);
        //echo $id;
        $bean = PFH_File::get($id);
        $result = PFH_File::get_link($f3, $bean);
        
        //$json = json_encode($result);
        $f3->set("json", $result);
        
        $template = new Template_json;
        echo $template->render("callback.js", 'text/javascript');
    }
    
    // ------------------------------------------------
    
    private function _validate_file($f3, $file) {
        
        // 檢查檔案大小
        if ($this->_validate_file_size($f3, $file) === FALSE) {
            return FALSE;
        }
        
        // 檢查檔案類型
        $acceptable_mine = $f3->get("UPLOAD.mimetype");
        
        // 沒設定不用檢查檔案類型
        if (is_array($acceptable_mine) === FALSE) {
            return TRUE;
        }
        $mine = $file['type'];
        if (in_array($mine, $acceptable_mine) === FALSE) {
            //echo "f";
            return FALSE;
        }
        
        //echo "t";
        return TRUE;
    }
    
    private function _validate_file_size($f3, $file) {
        
        // 沒設定不用檢查
        if ($f3->exists("UPLOAD.filesize") === FALSE
                || $f3->get("UPLOAD.filesize") < 1) {
            return TRUE;
        }
        
        // 檢查檔案
        $filesize = $f3->get("UPLOAD.filesize");
        $filesize = PFH_File_helper::convert_filesize_in_bytes($filesize);
        if ($file['size'] > $filesize) {
            return FALSE;
        }
        else {
            return TRUE;
        }
    }    
}
