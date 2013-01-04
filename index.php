<?php

include 'Library/__init__.php';

$c = new Controller(ROOT_PATH.'/Controllers', '/');

$url = isset($_GET['url']) ? $_GET['url'] : '';
if ($_SERVER['SCRIPT_URL'] != '/' && strpos($_SERVER['SCRIPT_URL'], 'index.php') === false) {
    $url = $_SERVER['SCRIPT_URL'];
    $t = explode('/', SITE_URL, 4);
    if (isset($t[3])) {
        $url = ltrim($url, '/');
        $url = substr($url, strlen($t[3]));
    }
}

try {
    $c->run($url);
    
} catch (Exception $e) {
    print_r($e);
}

exit;