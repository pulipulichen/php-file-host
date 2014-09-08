<?php
/**
 * PFH_ZIP
 * 
 * 檔案
 *
 * @author Pulipuli Chen <pulipuli.chen@gmail.com>
 * @version 20140908
 */
class PFH_ZIP {
        
    static public function create($f3, $md5) {
        
        $zip = new ZipArchive();

        $path = PFH_MD5::get_file_path($f3, $md5);
        $file_name = PFH_MD5::get_file_name($f3, $md5);
        $path_zip = $path . "-temp.zip";
        
        //$za->open($path_zip);
        if ($zip->open($path_zip, ZipArchive::CREATE)!==TRUE) {
            exit("cannot open <$path_zip>\n");
        }
        
        //echo "建立了zip檔案";
        
        $file_name = PFH_MD5::get_file_name($f3, $md5);
        
        $zip->addFile($path, "/" . $file_name);
        $zip->close();
        
        //echo "準備刪除檔案：$path";
        
        unlink($path);
        
        //echo "準備移動檔案：$path_zip";
        
        rename($path_zip, $path);
    }
    
    static public function read($f3, $md5) {
        
        $path_temp = PFH_MD5::get_tmp_file_path($f3, $md5);
        
        if (is_file($path_temp) === FALSE) {

            $path = PFH_MD5::get_file_path($f3, $md5);
            $tmp_dir = PFH_MD5::get_tmp_dir_path($f3, $md5);
            //$tmp_file = PFH_MD5::get_tmp_file_path($f3, $md5);
            
            $zip = new ZipArchive();
            //echo $path;
            $zip->open($path);
            //$zip->extractTo($tmp_file);
            $zip->extractTo($tmp_dir);
        } 

        return $path_temp;
    }
}
