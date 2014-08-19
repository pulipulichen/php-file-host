<?php
/**
 * PFH_Log_model
 * 
 * 記錄文件
 *
 * @author Pulipuli Chen <pulipuli.chen@gmail.com>
 * @version 20140819
 */
class PFH_Log_model {
    
    /**
     * 記錄下載對象
     * @param Object $f3
     * @param RedBean $file
     * @param String $action 動作
     * @return RedBean $file
     */
    static function create_log($f3,$file, $action) {
        
        $log = R::dispense("log");
        
        $log->file_id = $file->id;
        $log->action = $action;
        $log->datetime = R::isoDateTime();
        
        // 上傳IP
        $client_ip = PFH_Client_helper::get_client_ip($f3);
        //$data["client_ip"] = $client_ip;
        $log->client_ip = $client_ip;
        
        // 來源網頁
        $http_referer = getenv("HTTP_REFERER");
        //$data["http_referer"] = $http_referer;
        $log->http_referer = $http_referer;
        
        $log->agent = $f3->get("AGENT");
        
        R::store($log);
        
        return $file;
    }
}
