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
        $filepath = PFH_File_model::get_file_path_from_md5($f3, $md5);
        
        //$disposition = 'attachment';
        //header("Content-Disposition: $disposition; filename=\"download.txt\"");
        
        // send() method returns FALSE if file doesn't exist
        //if (!Web::instance()->send($filepath, $filetype)) {
            // Generate an HTTP 404
        //    $f3->error(404);
        //}
        
        PFH_File_model::add_download_count($file);
        
        PFH_File_helper::download_contents($filepath, $filetype, $filename);
    }
    
    
}
