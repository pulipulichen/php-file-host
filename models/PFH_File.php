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
     * 取得檔案
     * @param Int|String $id
     * @return RedBean
     */
    static function get($id) {
        if (is_string($id)) {
            $id = Base56::decode($id);
        }
        
        $bean = R::load( 'file', $id );
        return $bean;
    }

    static function create_from_upload($f3, $file, $md5 = NULL) {
        $bean = R::dispense("file");
        //$file->title="哈利波特";
        //$id = R::store($file);
        //$data = array();
        
        // 檔案名稱
        $filename = $file['name'];
        //$data["filename"] = $filename;
        $bean->filename = $filename;
        
        // 檔案大小
        $filesize = $file['size'];
        //$data["filesize"] = $filesize;
        $bean->filesize = $filesize;
        
        // 檔案類型
        $filetype = $file['type'];
        //$data["filetype"] = $filetype;
        $bean->filetype = $filetype;
        
        // md5
        // $md5
        if (is_null($md5)) {
            $md5 = md5_file($file["tmp_name"]);
        }
        //$data["md5"] = $md5;
        $bean->md5 = $md5;
        
        // 上傳IP
        $client_ip = PFH_Client_helper::get_client_ip($f3);
        //$data["client_ip"] = $client_ip;
        $bean->client_ip = $client_ip;
        
        // 來源網頁
        $http_referer = getenv("HTTP_REFERER");
        //$data["http_referer"] = $http_referer;
        $bean->http_referer = $http_referer;
        
        // 上傳日期
        $upload_date = R::isoDateTime();
        //$data["upload_date"] = $upload_date;
        $bean->upload_date = $upload_date;
        
        // 是否刪除
        $deleted = FALSE;
        //$data["deleted"] = $deleted;
        $bean->deleted = $deleted;
                
        //$hash = Base56::encode(1000000);
        //$data["hash"] = $hash;
        
        
        //R::store($bean);
        $bean->id = 9;
        
        echo $bean->id;
        
        return $bean;
    }
    
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
    
    /**
     * 
     * @param Object $f3
     * @param RedBean $bean
     * @return string 連結
     */
    static function get_link($f3, $bean) {
        
        //$link = "/get/1000/檔案名稱.txt";
        
        //$id = $bean->id;
        $id = 4;
        $hash_id = Base56::encode($id);
        
        $filename = $bean->filename;
        $filename = urlencode($filename);
        
        $link = "/get/" . $hash_id . "/" . $filename;
        
        $link = PFH_URL_helper::get_base_url($f3, $link);
        
        return $link;
    }
}
