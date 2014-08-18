<?php
/**
 * File_host
 * 
 * 檔案上傳
 *
 * @author pudding
 */
class File_host {
    function upload($f3) {
        //print_r($f3->get('POST'));
        //echo $f3->exists('POST.upload')."12121";
        if ($f3->exists("POST.upload")) {
            echo "file uploaded";
        }
        else {
            $f3->reroute("/");
        }
    }
}
