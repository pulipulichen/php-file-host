<?php
// 必要的載入
$f3 = require('lib/base.php');

// 設定檔案
$f3->config('config/globals.ini');
$f3->config('config/database.ini');
//$f3->config('config/uploads.ini');
require_once("config/uploads.php");
$f3->config('config/routes.ini');

// 資料庫
require_once("helpers/Database_helper.php");

// 正式執行
$f3->run();

/**
 * Handling File Downloads

F3 has a utility for sending files to an HTTP client, i.e. fulfilling download requests. You can use it to hide the real path to your download files. This adds some layer of security because users won't be able to download files if they don't know the file names and their locations. Here's how it's done:-

$f3->route('GET /downloads/@filename',
    function($f3,$args) {
        // send() method returns FALSE if file doesn't exist
        if (!Web::instance()->send('/real/path/'.$args['filename']))
            // Generate an HTTP 404
        $f3->error(404);
    }
);
 */
