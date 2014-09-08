<?php
/**
 * PFH_MD5
 * 
 * 檔案
 *
 * @author Pulipuli Chen <pulipuli.chen@gmail.com>
 * @version 20140908
 */
class PFH_MD5 {
 
    /**
     * 從MD5取得檔案路徑
     * 
     * @param Object $f3
     * @param String $md5
     * @return string
     * @author Pulipuli Chen <pulipuli.chen@gmail.com>
     * @version 20140819
     */
    static public function get_file_path($f3, $md5) {
        $path1 = substr($md5, 0, 2);
        $path2 = substr($md5, 2, 2);
        $file_name = substr($md5, 4);
        //$file_name = $md5;
        
        
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
     * 從MD5取得檔案路徑
     * 
     * @param Object $f3
     * @param String $md5
     * @return string
     * @author Pulipuli Chen <pulipuli.chen@gmail.com>
     * @version 20140908
     */
    static public function get_file_name($f3, $md5) {
        $file_name = substr($md5, 4);
        
        return $file_name;
    }
    
    /**
     * 從MD5取得檔案路徑
     * 
     * @param Object $f3
     * @param String $md5
     * @return string
     * @author Pulipuli Chen <pulipuli.chen@gmail.com>
     * @version 20140908
     */
    static public function get_tmp_file_path($f3, $md5) {
        $file_dir = self::get_tmp_dir_path($f3, $md5);
        $file_name = substr($md5, 4);
        $file_path = $file_dir . $file_name;
        
        return $file_path;
    }
    
    /**
     * 從MD5取得檔案路徑
     * 
     * @param Object $f3
     * @param String $md5
     * @return string
     * @author Pulipuli Chen <pulipuli.chen@gmail.com>
     * @version 20140908
     */
    static public function get_tmp_dir_path($f3, $md5) {
        $path1 = substr($md5, 0, 2);
        $path2 = substr($md5, 2, 2);
        
        $file_dir = sys_get_temp_dir()
        //$file_dir = $f3->get("UPLOADS") . "tmp/"
                . "PFH/"
                . $path1 . "/"
                . $path2 . "/";
        
        if (is_dir($file_dir) === FALSE) {
            mkdir($file_dir, 0700, true);
        }
        
        return $file_dir;
    }
}
