<?php
/**
 * File_manage
 * 
 * 檔案下載與刪除
 *
 * @author pudding
 */
class File_manage {
    
    function get_file($f3) {
        $hash_id = $f3->get("PARAMS.hash_id");
        $id = Base56::decode($hash_id);
        
        $file = PFH_File_model::get($id);
        
        $filename = $file->filename;
        $filetype = $file->filetype;
        
        $md5 = $file->md5;
        //$filepath = PFH_MD5::get_file_path($f3, $md5);
        //$filepath = PFH_ZIP::read($f3, $md5);
        //$filepath = PFH_MD5::get_file_path($f3, $md5);
        $filepath = PFH_Archive::read($f3, $md5);
        
        $action = "download";
        PFH_Log_model::create_log($f3, $file, $action);
        
        PFH_File_helper::download_contents($filepath, $filetype, $filename);
    }
    
    
}
