<?php
session_start();
require_once 'Routing.php';

$path = trim($_SERVER['REQUEST_URI'], '/');
$path = parse_url($path, PHP_URL_PATH);

Routing::get('', 'DefaultController');
Routing::get('index', 'DefaultController');
Routing::get('dashboard', 'SubgroupController');
Routing::get('wdpai', 'StatementController');
Routing::get('subgroup', 'SubgroupController');
// Routing::get('subgroup/{id}', 'SubgroupController');
Routing::post('login', 'SecurityController');
Routing::post('addStatement', 'StatementController');
Routing::run($path);

?>