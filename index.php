<?php
require_once 'Routing.php';

$path = trim($_SERVER['REQUEST_URI'], '/');
$path = parse_url($path, PHP_URL_PATH);

Routing::get('', 'DefaultController');
Routing::get('index', 'DefaultController');
Routing::get('dashboard', 'SubgroupController');
Routing::get('subgroup', 'SubgroupController');
Routing::get('register', 'DefaultController');
Routing::post('signIn', 'SecurityController');
Routing::post('signUp', 'SecurityController');
Routing::post('logout', 'SecurityController');
Routing::post('addStatement', 'StatementController');
Routing::run($path);

?>