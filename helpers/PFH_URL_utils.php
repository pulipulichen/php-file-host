<?php
/**
 * PFH_URL_utils
 * 
 * 網址相關的零零總總
 *
 * @author Pulipuli Chen <pulipuli.chen@gmail.com>
 */
class PFH_URL_utils {
    
    /**
     * 取得現在的網址
     * @param Object $f3
     * @param string $route 要處理的路徑
     * @return string
     */
    static function get_base_url($f3, $route = NULL) {
        
        $protocol = $_SERVER["REQUEST_SCHEME"];
        $host = $_SERVER["HTTP_HOST"];
        $port = $_SERVER["SERVER_PORT"];
        $uri = $f3->get("BASE");
        
        $url = $protocol . "://" . $host;
        if (isset($port) 
                && $port !== "" 
                && $port !== "80") {
            $url = $url . ":" . $port;
        }
        
        $url = $url . $uri;
        
        if (is_string($route)) {
            if (substr($route, 0, 1) !== "/") {
                $route = "/" . $route;
            }
            $url = $url . $route;
        }
        
        return $url;
    }
}
