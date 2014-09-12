<?php
/**
 * PFH_Archive_cache
 * 
 * 檔案
 *
 * @author Pulipuli Chen <pulipuli.chen@gmail.com>
 * @version 20140912
 */
class PFH_Archive_cache {
    
    static public function add($f3, $path) {
        $cache_file = R::findOne("cache", "path = ?", [$path]);
        if (is_null($cache_file)) {
            $cache_file = R::dispense("cache");
            $cache_file->path = $path;
            $cache_file->filesize = filesize($path);
        }
        $cache_file->datetime = R::isoDateTime();
        R::store($cache_file);
    }
    
    static public function clean($f3, $filename) {
        $total_filesize = R::getCell('select sum(filesize) as total_filesize from cache');
        
        $cache_total_filesize_limit = $f3->get("UPLOAD.cache_total_size_limit");
        $cache_total_filesize_limit = PFH_File_helper::convert_filesize_in_bytes($cache_total_filesize_limit);
        
        if ($total_filesize > $cache_total_filesize_limit) {
            
            $caches = R::find("cache", "ORDER BY datetime");
            $count = count($caches); 
            
            // 只有一個不刪除
            //if ($count < 2) {
            //    return;
            //}
            
            foreach ($caches AS $key => $cache) {
                
            
                //不刪除最後一個
                //if ($key > $count - 1) {
                //    return;
                //}
                if ($cache->path === $filename) {
                    continue;
                }
                
            //throw new Exception("$key $cache->path");
            
                 //echo $cache->path . "<br />";
                if (is_file($cache->path)) {
                    unlink($cache->path);
                }
                 $total_filesize = $total_filesize - $cache->filesize;
                 R::trash( $cache );
                 
                 if ($total_filesize < $cache_total_filesize_limit) {
                     break;
                 }
             }
        }
    }
}

