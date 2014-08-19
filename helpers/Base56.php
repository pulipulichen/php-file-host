<?php
/**
 * Base56
 * 
 * 壓縮方法
 *
 * 程式碼來源是 http://rossduggan.ie/blog/codetry/base-56-integer-encoding-in-php/
 * @author Pulipuli Chen <pulipuli.chen@gmail.com>
 */
class Base56 {
    
    // URL保留文字 http://en.wikipedia.org/wiki/Percent-encoding#Types_of_URI_characters
    static $alphabet_raw = '0123456789abcdefghijkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ_-.~';
    
    /**
     * 編碼
     * Encode a number in Base X
     * 
     * @param Int $num 要編碼的數字 The number to encode
     * @param String $alphabet_raw 對應編碼表 The alphabet to use for encoding
     * @return String
     * @author Pulipuli Chen <pulipuli.chen@gmail.com>
     * @version 20140819
     */
    static function encode($num, $alphabet_raw = NULL) {
        if ($alphabet_raw === NULL) {
            $alphabet_raw = Base56::$alphabet_raw;
        }
        
        $alphabet = str_split($alphabet_raw);
        if ($num === 0){
            return 0;
        }

        $n = str_split($num);
        $arr = array();
        $base = sizeof($alphabet);

        while($num){
            $rem = $num % $base;
            $num = (int)($num / $base);
            $arr[]=$alphabet[$rem];
            }

        $arr = array_reverse($arr);
        return implode($arr);
    }
    
    /**
     * 解碼
     * Decode a Base X encoded string into the number
     * 
     * @param String $string 要解碼的字串 The encoded string
     * @param String $alphabet_raw 對應編碼表 The alphabet to use for encoding
     * @return String
     * @author Pulipuli Chen <pulipuli.chen@gmail.com>
     * @version 20140819
     */
    static function decode($string, $alphabet_raw = NULL){
        if ($alphabet_raw === NULL) {
            $alphabet_raw = Base56::$alphabet_raw;
        }
        
        $alphabet = str_split($alphabet_raw);

        $base = sizeof($alphabet);
        $strlen = strlen($string);
        $num = 0;
        $idx = 0;

        $s = str_split($string);
        $tebahpla = array_flip($alphabet);

        foreach($s as $char){
            $power = ($strlen - ($idx + 1));
            $num += $tebahpla[$char] * (pow($base,$power));
            $idx += 1;
        }
        return $num;
    }
}
