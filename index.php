<?php
require_once 'Routing.php';

$path = trim($_SERVER['REQUEST_URI'], '/');
$path = parse_url($path, PHP_URL_PATH);

Routing::get('', 'DefaultController');
Routing::get('index', 'DefaultController');
Routing::get('register', 'DefaultController');

Routing::get('createGroup', 'GroupController');
Routing::get('createSubgroup', 'GroupController');
Routing::get('createThread', 'GroupController');

Routing::get('dashboard', 'SubgroupController');
Routing::get('subgroup', 'SubgroupController');

Routing::get('optInGroup', 'SettingsModificationController');
Routing::get('optOutGroup', 'SettingsModificationController');
Routing::get('optOutSubgroup', 'SettingsModificationController');
Routing::get('optInSubgroup', 'SettingsModificationController');
Routing::get('optOutThread', 'SettingsModificationController');
Routing::get('optInThread', 'SettingsModificationController');

Routing::get('settings', 'SettingsController');

Routing::post('logout', 'SecurityController');
Routing::post('signIn', 'SecurityController');
Routing::post('signUp', 'SecurityController');

Routing::post('addStatement', 'StatementController');
Routing::post('editStatement', 'StatementController');
Routing::post('confirmStatement', 'StatementController');
Routing::post('undoConfirmStatement', 'StatementController');

Routing::post('changeUserData', 'SettingsModificationController');

Routing::run($path);

?>