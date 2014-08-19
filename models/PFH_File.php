<?php
/**
 * PFH_File
 * 
 * 檔案
 *
 * @author Pulipuli Chen <pulipuli.chen@gmail.com>
 * @version 20140819
 */
class PFH_File {
    
    /**
     * 從MD5取得檔案路徑
     * 
     * @param Object $f3
     * @param String $md5
     * @return string
     * @author Pulipuli Chen <pulipuli.chen@gmail.com>PFH_File
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
}
