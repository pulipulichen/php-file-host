<?php
/**
 * File_host
 * 
 * 檔案上傳
 *
 * @author pudding
 */
class File_host {
    function upload($f3) {
        $file = $_FILES["file"];
        //var_dump($file);
        
        $validate_result = $this->_validate_file($f3, $file);
        
        $result = FALSE;
        
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
            $result = PFH_File::get_link($f3, $bean);
        }
        
        $json = json_encode($result);
        $f3->set("json", $json);
        
        $template = new Template;
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
        $mine = $file['type'];
        if (in_array($mine, $acceptable_mine) === FALSE) {
            //echo "f";
            return FALSE;
        }
        
        //echo "t";
        return TRUE;
    }
    
    private function _validate_file_size($f3, $file) {
        // 檢查檔案
        $filesize = $f3->get("UPLOAD.filesize");
        $filesize = PFH_File_utils::convert_filesize_in_bytes($filesize);
        if ($file['size'] > $filesize) {
            return FALSE;
        }
        else {
            return TRUE;
        }
    }
    
    // ---------------------------------------------
    
    private function _db_record_create($f3, $file, $md5) {
        $data = array();
        
        // 檔案名稱
        $filename = $file['name'];
        $data["filename"] = $filename;
        
        // 檔案大小
        $filesize = $file['size'];
        $data["filesize"] = $filesize;
        
        // 檔案類型
        $filetype = $file['type'];
        $data["filetype"] = $filetype;
        
        // md5
        // $md5
        $data["md5"] = $md5;
        
        // 上傳IP
        $client_ip = PFH_Client_utils::get_client_ip();
        $data["client_ip"] = $client_ip;
        
        // 來源網頁
        $http_referer = getenv("HTTP_REFERER");
        $data["http_referer"] = $http_referer;
        
        // 上傳日期
        $upload_date = R::isoDateTime();
        $data["upload_date"] = $upload_date;
        
        // 是否刪除
        $deleted = FALSE;
        $data["deleted"] = $deleted;
        
        $hash = Base56::encode(1000000);
        $data["hash"] = $hash;
        
        
        var_dump($data);
        
        
    }
    
}
