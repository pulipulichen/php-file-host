<?php
/**
 * File_utils
 * 
 * 檔案的零零總總
 *
 * @author Pulipuli Chen <pulipuli.chen@gmail.com>
 * @version 20140819
 */class PFH_File_utils {
    
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
