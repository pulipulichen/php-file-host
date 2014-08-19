<?php
/**
 * PFH_Ext_app_route
 * 
 * 繞路
 *
 * @author Pulipuli Chen <pulipuli.chen@gmail.com>
 */
class PFH_Ext_app_route {
    
    /**
     * 重繞外部路徑
     * @param type $f3
     * @return \PFH_Ext_app_route
     */
    public function route($f3) {
        $uri = $f3->get("URI");
        $uri = substr($uri, strrpos($uri, "/") + 1);
        $uri = "/ext_apps/" . $uri;
        
        $f3->reroute($uri);
        
        return $this;
    }
}
