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
        /*
        if ($f3->exists("POST.upload")) {
            echo "file uploaded";
        }
        else {
            $f3->reroute("/");
        }
        */
//        $overwrite = true; // set to true, to overwrite an existing file; Default: false
//        $slug = true; // rename file to filesystem-friendly version
//        
//        $web = \Web::instance();
//        $files = $web->receive(function($file,$formFieldName){
//                var_dump($file);
//                /* looks like:
//                  array(5) {
//                      ["name"] =>     string(19) "csshat_quittung.png"
//                      ["type"] =>     string(9) "image/png"
//                      ["tmp_name"] => string(14) "/tmp/php2YS85Q"
//                      ["error"] =>    int(0)
//                      ["size"] =>     int(172245)
//                    }
//                */
//                // $file['name'] already contains the slugged name now
//                
//                $file['name'] = 'text.txt';
//
//                // maybe you want to check the file size
//                if($file['size'] > (2 * 1024 * 1024)) { // if bigger than 2 MB
//                    return false; // this file is not valid, return false will skip moving it
//                }
//                
//                // everything went fine, hurray!
//                return true; 
//                // allows the file to be moved from php tmp dir to your defined upload dir
//            },
//            $overwrite,
//            $slug
//        );
//
//        var_dump($files);
        var_dump($_FILES["file"]);
        
        $tmp_path = $_FILES["file"]["tmp_name"];
        $md5 = md5_file($tmp_path);
        echo $md5;
        
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
        
        move_uploaded_file($tmp_path,
             $file_path);
        
    }
}
