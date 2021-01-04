<?php

require_once 'Routing.php';

$path = trim($_SERVER['REQUEST_URI'], '/');
$path = parse_url($path, PHP_URL_PATH);

Routing::get('', 'DefaultController');
Routing::get('index', 'DefaultController');  // TODO: usunąć, jeśli niepotrzebne
Routing::get('dashboard', 'DefaultController');
Routing::get('wdpai', 'StatementController');
Routing::post('login', 'SecurityController');
Routing::post('addStatement', 'StatementController');
Routing::run($path);

?>