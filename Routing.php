<?php
require_once 'src/controllers/DefaultController.php';
require_once 'src/controllers/GroupController.php';
require_once 'src/controllers/SecurityController.php';
require_once 'src/controllers/SettingsController.php';
require_once 'src/controllers/SettingsModificationController.php';
require_once 'src/controllers/StatementController.php';
require_once 'src/controllers/SubgroupController.php';

class Routing {
    public static $routes;

    public static function get($url, $view) {
        self::$routes[$url] = $view;
    }

    public static function post($url, $view) {
        self::$routes[$url] = $view;
    }

    public static function run($url) {
        $urlParts = explode('/', $url); 
        $action = $urlParts[0];

        if(!array_key_exists($action, self::$routes)) {
            die("Wrong url!");  // TODO: 404
        }

        $controllerClass = self::$routes[$action];
        $object = new $controllerClass;
        $action = $action ?: 'index';

        $id = $urlParts[1] ?? '';
        // if (strcmp($action, 'subgroup') == 0) {
            // $object->$action(explode('/', $url)[1]);
        // throw new Exception('debug' . explode('/', $url)[1] . 'debug');
        // }
        $object->$action($id);

    }
}

?>