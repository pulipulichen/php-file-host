<?php
/**
 * String_helper
 * 
 * 字串處理的林林總總
 *
 * @author Pulipuli Chen <pulipuli.chen@gmail.com>
 */
class String_helper {
    
    /**
     * 比對字串開頭
     * 
     * 參考自 http://stackoverflow.com/questions/834303/startswith-and-endswith-functions-in-php
     * 
     * @param string $haystack
     * @param string $needle
     * @return boolean
     */
    static public function starts_with($haystack, $needle) {
        return $needle === "" 
                || strpos($haystack, $needle) === 0;
    }
    
    /**
     * 比對字串結尾
     * 
     * 參考自 http://stackoverflow.com/questions/834303/startswith-and-endswith-functions-in-php
     * 
     * @param string $haystack
     * @param string $needle
     * @return boolean
     */
    static public function ends_with($haystack, $needle) {
        return $needle === "" 
                || substr($haystack, -strlen($needle)) === $needle;
    }
}