<?php
/**
 * PFH_Archive
 * 
 * 檔案
 *
 * @author Pulipuli Chen <pulipuli.chen@gmail.com>
 * @version 20140908
 */
class PFH_Archive {
    
    static public $ext = array(
        "ZIP" => "zip",
        "BZIP" => "bz2"
    );

    static public function create($f3, $md5, $filename) {
        
        $compression = $f3->get("UPLOAD.compression");
        
        $path = PFH_MD5::get_file_path($f3, $md5, $filename);
        $file_name = PFH_MD5::get_file_name($f3, $md5, $filename);
        $path_archive = $path . "." . self::$ext[$compression];
        
        //echo $path_archive;
        //throw "1212";
        
        if ($compression === "ZIP") {
            $archive = new ZipArchive();
            
            //$za->open($path_zip);
            if ($archive->open($path_archive, ZipArchive::CREATE)!==TRUE) {
                exit("cannot open <$path_archive>\n");
            }

            //echo "建立了zip檔案";

            $file_name = PFH_MD5::get_file_name($f3, $md5);
            $archive->addFile($path, $file_name);
            $archive->close();
            //echo 12121212;
            //throw new Exception($file_name."|".$path_archive);
        }
        else if ($compression === "BZIP") {
            $in_file = fopen ($path, "r"); 
            $out_file = bzopen ($path_archive, "w"); 

            while (!feof ($in_file)) { 
                $buffer = fgets ($in_file, 4096); 
                 bzwrite ($out_file, $buffer, 4096); 
            } 

            fclose ($in_file); 
            bzclose ($out_file);
        }
        
        //echo "準備刪除檔案：$path";
        
        unlink($path);
        
        //echo "準備移動檔案：$path_zip";
        
        // 不重新命名
        //rename($path_archive, $path);
        return $path_archive;
    }
    
    static public function read($f3, $md5, $filename) {
        
        $path_temp = PFH_MD5::get_tmp_file_path($f3, $md5, $filename);
        
        if (is_file($path_temp) === FALSE) {

            $path = PFH_MD5::get_file_path($f3, $md5, $filename);
            $tmp_dir = PFH_MD5::get_tmp_dir_path($f3, $md5);
            //$tmp_file = PFH_MD5::get_tmp_file_path($f3, $md5);
            
            if (is_file($path)) {
                // 沒有壓縮的情況
                return $path;
            }   //if (is_file($path)) {
            
            if (is_file($path . ".zip")) {
                $archive = new ZipArchive();
                //echo $path;
                $res = $archive->open($path . ".zip");
                //$zip->extractTo($tmp_file);

                $archive->extractTo($tmp_dir);
                $archive->close();
            }
            else if (is_file($path . ".bz2")) {
                $in_file = bzopen ($path . ".bz2", "r"); 
                $out_file = fopen ($path_temp, "w"); 

                while ($buffer = bzread ($in_file, 4096)) { 
                    fwrite ($out_file, $buffer, 4096); 
                } 

                bzclose ($in_file); 
                fclose ($out_file); 
            }
                
        }
        return $path_temp;
    }
}

