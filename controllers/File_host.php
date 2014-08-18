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
        var_dump($file);
        
        $validate_result = $this->_validate_file($f3, $file);
        
        if ($validate_result === TRUE) {
            $tmp_path = $_FILES["file"]["tmp_name"];
            $md5 = md5_file($tmp_path);

            echo $md5;



            $file_path = $this->_get_file_path_from_md5($f3, $md5);

            move_uploaded_file($tmp_path,
                 $file_path);
            
            
        }
        else {
            // upload faild
        }
    }
    
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
        $filesize = $this->_get_filesize_in_bytes($filesize);
        if ($file['size'] > $filesize) {
            return FALSE;
        }
        else {
            return TRUE;
        }
    }
    
    private function _get_file_path_from_md5($f3, $md5) {
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
    
    private function _get_filesize_in_bytes($filesize) {
        if (is_int($filesize)) {
            return $filesize;
        }
        else {
            $filesize = strtoupper($filesize);
            $footer_len = 0;
            $multiple = 0;
            if (strpos($filesize, "B") !== FALSE) {
                $footer_len = 1;
            }
            else if (strpos($filesize, "KB") !== FALSE) {
                $footer_len = 2;
                $multiple = 1;
            }
            else if (strpos($filesize, "K") !== FALSE) {
                $footer_len = 1;
                $multiple = 1;
            }
            else if (strpos($filesize, "MB") !== FALSE) {
                $footer_len = 2;
                $multiple = 2;
            }
            else if (strpos($filesize, "M") !== FALSE) {
                $footer_len = 1;
                $multiple = 2;
            }
            else if (strpos($filesize, "GB") !== FALSE) {
                $footer_len = 2;
                $multiple = 3;
            }
            else if (strpos($filesize, "G") !== FALSE) {
                $footer_len = 1;
                $multiple = 3;
            }
            else if (strpos($filesize, "TB") !== FALSE) {
                $footer_len = 2;
                $multiple = 4;
            }
            else if (strpos($filesize, "T") !== FALSE) {
                $footer_len = 1;
                $multiple = 4;
            }
            else if (strpos($filesize, "PB") !== FALSE) {
                $footer_len = 2;
                $multiple = 5;
            }
            else if (strpos($filesize, "P") !== FALSE) {
                $footer_len = 1;
                $multiple = 5;
            }
            
            $filesize = substr($filesize, 0, strlen($filesize) - $footer_len);
            $filesize = intval($filesize);
            
            $multiple = pow(1024, $multiple);
            $filesize = $filesize * $multiple;
            return $filesize;
        }
        
    }
}
