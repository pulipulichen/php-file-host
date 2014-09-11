<?php
/**
 * File_helper
 * 
 * 檔案的零零總總
 *
 * @author Pulipuli Chen <pulipuli.chen@gmail.com>
 * @version 20140819
 */class PFH_File_helper {
    
    /**
     * 將檔案大小換算成byte
     * @param String $filesize
     * @return Int
     * @author Pulipuli Chen <pulipuli.chen@gmail.com>
     * @version 20140819
     */
    static function convert_filesize_in_bytes($filesize) {
        if (is_int($filesize)) {
            return $filesize;
        }
        else {
            $filesize = strtoupper($filesize);
            $footer_len = 0;
            $multiple = 0;
            if (strpos($filesize, "KB") !== FALSE) {
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
            else if (strpos($filesize, "B") !== FALSE) {
                $footer_len = 1;
            }
            
            $filesize = substr($filesize, 0, strlen($filesize) - $footer_len);
            $filesize = intval($filesize);
            
            $multiple = pow(1024, $multiple);
            $filesize = $filesize * $multiple;
            return $filesize;
        }
    }
    
    /**
     * download_contents
     * 
     * 執行下載的動作
     * 
     * SAVR 10/20/06 : force file download over SSL for IE
     * BIP  09/17/07 : inserted and tested for ProjectPier 
     * 
     * @param String $content 檔案位置
     * @param String $type MIME
     * @param String $name 檔案名稱
     * @param Boolean $force_download 強制下載
     * 
     * @author Pulipuli Chen <pulipuli.chen@gmail.com>
     * @version 20140819
     */
    static public function download_contents($content, $type, $name, $force_download = TRUE) {
        
        // 加入檔案是否存在的檢查
        if (is_file($content) === FALSE) {
            throw new Exception("file not exists");
        }
        
        $chunksize = 1*(1024*1024); // how many bytes per chunk
        $buffer = '';
        $handle = fopen($content, 'rb');
        

        $size = filesize($content);
        //echo $size;
        self::_download_headers($name, $type, $size, $force_download);

        if ($handle === false) {
            return false;
        }
        while (!feof($handle)) {
            $buffer = fread($handle, $chunksize);
            print $buffer;
            flush();
            ob_flush();
        }
        return fclose($handle);
    } // download_contents
    
    /**
     * 檔案下載的Header
     * 
     * @param String $name 檔案名稱
     * @param String $type 檔案類型
     * @param Int $size 檔案大小
     * @param type $force_download 強制下載
     * 
     * @author Pulipuli Chen <pulipuli.chen@gmail.com>
     * @version 20140819
     */
    static private function _download_headers($name, $type, $size, $force_download = true) {
        if ($force_download) {
            /** SAVR 10/20/06
            * Was:
            * header("Cache-Control: public");
            */
            header("Cache-Control: public, must-revalidate");
            if (strstr($_SERVER['HTTP_USER_AGENT'], "MSIE") == false) {
                  header("Pragma: hack");
            }
            else {
                header('Pragma: public');
            }
        } else {
          header("Cache-Control: no-store, no-cache, must-revalidate");
          header("Cache-Control: post-check=0, pre-check=0", false);
          if (strstr($_SERVER['HTTP_USER_AGENT'], "MSIE") == false) {
                header("Pragma: no-cache");
          }
          else {
              header('Pragma: public');
          }
        } // if
        header("Expires: " . gmdate("D, d M Y H:i:s", mktime(date("H") + 2, date("i"), date("s"), date("m"), date("d"), date("Y"))) . " GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Content-Type: $type");
        header("Content-Length: $size");
        // Prepare disposition
        $disposition = $force_download ? 'attachment' : 'inline';
        // http://www.ietf.org/rfc/rfc2183.txt
        $download_name = strtr($name, " ()<>@,;:\\/[]?=*%'\"", '--------------------');
        //$download_name = normalize($download_name);
        // Generate the server headers
        if (strstr($_SERVER['HTTP_USER_AGENT'], "MSIE")) {
            $download_name = iconv('utf-8', 'big5', $download_name);
            header('Pragma: public');
        }
        header("Content-Disposition: $disposition; filename=\"$download_name\"");
        //header("Content-Disposition: $disposition; filename=$download_name");
        header("Content-Transfer-Encoding: binary");
    }
    
    /**
     * 取得根目錄
     * @param Object $f3
     * @param String|NULL $filename
     * @return string
     */
    static public function get_root_dir($f3, $filename = NULL) {
        $root = $f3->get("ROOT");
        $base = $f3->get("BASE");
        $base = substr($base, 1);
        
        $path = $root . DIRECTORY_SEPARATOR . $base . DIRECTORY_SEPARATOR;
        if (is_string($filename)) {
            $path = $path . $filename;
        }
        return $path;
    }
}
