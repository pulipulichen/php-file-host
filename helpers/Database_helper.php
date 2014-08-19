<?php

$database_host = $f3->get('DATABASE.host');
$db = NULL;
if (strpos($database_host, "sqlite:") === 0) {
    // 設置f3的資料庫
    $db = new \DB\SQL($database_host);
}
else {
    // 設置f3的資料庫
    $db = new \DB\SQL($database_host
            , $f3->get('DATABASE.user')
            , $f3->get('DATABASE.password'));
}
$f3->set('DB', $db);
new \DB\SQL\Session($db);

//$f3->set('SESSION.test',123);
//echo $f3->get('SESSION.test');

// 讀取RedBean資料庫
require_once('redbeanphp/rb_setup.php');