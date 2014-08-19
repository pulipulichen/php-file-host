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
            $file_path = $this->_get_file_path_from_md5($f3, $md5);

            if (is_file($file_path) === FALSE) {
                move_uploaded_file($tmp_path,
                     $file_path);
            }
          
            $result = $this->_db_record_create($f3, $file, $md5);
            $result = "http://www.googl.com.tw";
        }
        
        $json = json_encode($result);
        $f3->set("json", $json);
        
        $template = new Template;
        echo $template->render("callback.js", 'text/javascript');
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
        $client_ip = $this->_get_client_ip();
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
        
        $hash = base_convert(
            sprintf('%u',crc32(1000)),10,36
        );
        //$hash = strtr(base64_encode(1000), '+/=', '-_,');
        $hash = $this->base56_encode(100000000);
        $data["hash"] = $hash;
        
        
        var_dump($data);
        
        
    }
    
    /**
     * 取得使用者的IP資訊
     * @return String
     */
    private function _get_client_ip()
    {
        $myip = NULL;
        if (empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $myip = $_SERVER['REMOTE_ADDR'];
        } else {
            $myip = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $myip = $myip[0];
        }
        
        if ($myip === "::1") {
            $myip = NULL;
        }
        return $myip;
    }
    
    // URL保留文字 http://en.wikipedia.org/wiki/Percent-encoding#Types_of_URI_characters
    var $_alphabet_raw = '0123456789abcdefghijkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ_-.~';
    
    function base56_encode($num){
        $alphabet = str_split($this->_alphabet_raw);
        
        /*
            Encode a number in Base X

        `num`: The number to encode
        `alphabet`: The alphabet to use for encoding
        */
        if ($num == 0){
            return 0;
            }

            $n = str_split($num);
        $arr = array();
        $base = sizeof($alphabet);

        while($num){
            $rem = $num % $base;
            $num = (int)($num / $base);
            $arr[]=$alphabet[$rem];
            }

        $arr = array_reverse($arr);
        return implode($arr);
    }

    function base56_decode($string){
        $alphabet = str_split($this->_alphabet_raw);
        /*
            Decode a Base X encoded string into the number

        Arguments:
        - `string`: The encoded string
        - `alphabet`: The alphabet to use for encoding
        */

        $base = sizeof($alphabet);
        $strlen = strlen($string);
        $num = 0;
        $idx = 0;

            $s = str_split($string);
            $tebahpla = array_flip($alphabet);

        foreach($s as $char){
            $power = ($strlen - ($idx + 1));
            $num += $tebahpla[$char] * (pow($base,$power));
            $idx += 1;
            }
        return $num;
    }
}
