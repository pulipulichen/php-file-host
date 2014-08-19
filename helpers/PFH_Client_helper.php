<?php
/**
 * Client_helper
 * 
 * 客戶端的零零總總
 *
 * @author pudding 20140819
 */
class PFH_Client_helper {
    
    /**
     * 取得使用者的IP資訊
     * @return String
     * @author Pulipuli Chen <pulipuli.chen@gmail.com>
     * @version 20140819
     * 
     */
    static function get_client_ip($f3)
    {
        $myip = NULL;
        if (isset($_SERVER) === FALSE) {
            return NULLL;
        }
        else if (empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $myip = $_SERVER['REMOTE_ADDR'];
        } else {
            $myip = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $myip = $myip[0];
        }
        
        //if ($myip === "::1") {
        //    $myip = NULL;
        //}
        return $myip;
    }
    
}