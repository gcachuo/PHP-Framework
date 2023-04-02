<?php
session_start();
list($modulo, $fn) = explode('/', trim(isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '', '/')) + [null, null];
$_POST = [
        'modulo' => $modulo ?: $_SESSION['modulo'],
        'fn' => $fn,
    ] + $_POST;
$data = [];
$message = 'Completed.';
$code = 200;
header('Content-Type: application/json');
http_response_code($code);
if (ob_get_contents()) ob_end_clean();

include_once 'index.inc';
