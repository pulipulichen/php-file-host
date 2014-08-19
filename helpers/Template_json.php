<?php
/**
 * Template_json
 * 
 * 擴增樣板
 * 參考網址：http://fatfreeframework.com/extended-templating
 * 
 * @author Pulipuli Chen <pulipuli.chen@gmail.com>
 * @version 20140819
 */
class Template_json extends \Template {

    /**
     * 編碼成JSON
     * @param Object $val
     * @return String
     */
    public function json_encode($val) {
        //$val = json_encode($val);
        return $val;
    }
}