<?php
require_once 'AppController.php';

class DefaultController extends AppController {

    public function index() {
        (new Session())->handleSession(false);
        $this->render('login');
    }

    public function register() {
        (new Session())->handleSession(false);
        $this->render('register');
    }
}

?>