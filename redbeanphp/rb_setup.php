<?php

require_once 'redbeanphp/rb.php';

$database_host = $f3->get('DATABASE.host');
if (strpos($database_host, "sqlite:") === 0) {
    R::setup($database_host);
}
else {
    R::setup($database_host
            , $f3->get('DATABASE.user')
            , $f3->get('DATABASE.password'));
}