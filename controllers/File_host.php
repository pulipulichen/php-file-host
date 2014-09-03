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
    private $session_error_key = "SESSION.upload_error";

    /**
     * 上傳檔案
     * @param Object $f3
     * @return boolean
     */
    public function upload($f3) {
        
        // 要設定這個header，才能做iframe
        header('X-Frame-Options: ');
        
        /*
        if ($f3->exists($this->session_key)) {
            $template = new Template_json;
            echo $template->render("mock_jquery_file_upload_handler.js", 'text/javascript');        
            return $this;
        }
        */
        //header("Access-Control-Allow-Origin: http://localhost");
        if (isset($_FILES["file"]) === FALSE) {
            if (isset($_POST["file"]) === FALSE) {
                throw new Exception("no file upload");
            }
            else {
                $data = substr($_POST['file'], strpos($_POST['file'], ",") + 1);
                $type = substr($_POST['file'], 5, strpos($_POST['file'], ";base64") - 5);
                $type = stripcslashes($type);
                $decodedData = base64_decode($data);
                $filename = urldecode($_POST['fname']);
                $tmp_name = sys_get_temp_dir() . DIRECTORY_SEPARATOR .$filename;
                //echo $tmp_name;
                $fp = fopen($tmp_name, 'wb');
                fwrite($fp, $decodedData);
                fclose($fp);
                
                $file = array(
                    "name" => $filename,
                    "size" => filesize($tmp_name),
                    "tmp_name" => $tmp_name,
                    "type" => $type
                );
            }
            //$f3->reroute("/");
            //return $this;
        }
        else {
            $file = $_FILES["file"];
        }
        
        //var_dump($_POST);
        //var_dump($_FILES);
        //echo $_POST["fileupload"];
        //$file = $_FILES["file"];
        
        //var_dump($file);
        
        try {
            $validate_result = $this->_validate_file($f3, $file);
        }
        catch (Exception $e) {
            $validate_result = $file["name"] . " upload error: " . $e->getMessage();
        }
        
        //$result = FALSE;
        
        
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
        
        $bean = NULL;
        if ($validate_result === TRUE) {
            //$result = $this->_db_record_create($f3, $file, $md5);
            $bean = PFH_File_model::create_from_upload($f3, $file);
            // 新增KEY到SESSION之中
            $f3->set($this->session_key, $bean->id);
            $f3->clear($this->session_error_key);
        }
        else {
            // 從SESSION刪除
            $f3->clear($this->session_key);
        }
        
        // -------------------------
        // reroute
        
        if ($f3->get("POST.local_upload") !== "1") {
            $reroute = "/get_link";
            if ($f3->exists("GET.callback")) {
                $reroute = $reroute . "?callback=" . $f3->get("GET.callback");
            }

            //echo $bean->id;
            $f3->reroute($reroute);
        }
        else {
            $data = array(
                'name' => $file['name'],
                'size' => $file['size']
            );
            if (is_null($bean) === FALSE) {
                $data["url"] = PFH_File_model::get_link($f3, $bean);
            }
            else {
                $data["error"] = $validate_result;
            }
            $f3->set("handle", $data);
            
            $template = new Template_json;
            echo $template->render("jquery_file_upload_handler.js", 'text/javascript');            
        }
        return $this;
    }
    
    public function get_link($f3) {
        
        // 要設定這個header，才能做iframe
        header('X-Frame-Options: ');
        
        if ($f3->exists($this->session_error_key)) {
            //throw new Exception($f3->get($this->session_error_key));
            $f3->set("json", $f3->get($this->session_error_key));
            $template = new Template_json;
            echo $template->render("callback.js", 'text/javascript');
            return;
        }
        
        if ($f3->exists($this->session_key) === FALSE) {
            throw new Exception("no file");
            //$f3->reroute("/");
            //return $this;
        }
        
        $id = $f3->get($this->session_key);
        //echo $id;
        $bean = PFH_File_model::get_by_id($id);
        $result = PFH_File_model::get_link($f3, $bean);
        
        //$json = json_encode($result);
        $f3->set("json", $result);
        
        $template = new Template_json;
        echo $template->render("callback.js", 'text/javascript');
        //echo $template->render("callback.js");
    }
    
    // ------------------------------------------------
    
    private function _validate_file($f3, $file) {
        
        // 檢查檔案大小
        if ($this->_validate_file_size($f3, $file) === FALSE) {
            return FALSE;
        }
        
        // 檢查檔案類型
        $mine = $file['type'];
        $acceptable_mine = $f3->get("UPLOAD.mimetype");
        
        // 沒設定不用檢查檔案類型
        if (is_array($acceptable_mine) === FALSE) {
            return TRUE;
        }
        
        $result = FALSE;
        foreach ($acceptable_mine AS $a_mine) {
            $a_mine = trim($a_mine);
            if ($a_mine === $mine) {
                $result = TRUE;
                break;
            }
        }
        
        if ($result === FALSE) {
            $message = "MIME type not accept: " . $mine;
            $f3->set($this->session_error_key, $message);
            throw new Exception($message);
        }
        else {
            return TRUE;
        }
        /*
        array_walk($config_acceptable_mine,function ($value, $key) {
            echo trim($value) . "|";
            $acceptable_mine[$key] = trim($value);
        });
        var_dump($acceptable_mine);
        if (in_array($mine, $acceptable_mine) === FALSE) {
            //echo "f";
            throw new Exception("file MIME type not accept: " . $mine);
            return FALSE;
        }
        //echo "t";
        return TRUE;
        */
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
            $message = "file size too large";
            $f3->set($this->session_error_key, $message);
            throw new Exception($message);
            return FALSE;
        }
        else {
            return TRUE;
        }
    }    
    
//    public function mock_handler($f3) {
//        
//        $template = new Template_json;
//        echo $template->render("mock_jquery_file_upload_handler.js", 'text/javascript');
//        return;
//        
//    }
    
    public function postmessage($f3) {
        
        // 要設定這個header，才能做iframe
        header('X-Frame-Options: ');
        
        $template = new Template;
        echo $template->render("postmessage.html", 'text/html');
        return;
    }
}
