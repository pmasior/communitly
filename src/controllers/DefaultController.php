<?php
require_once 'AppController.php';

class DefaultController extends AppController {

    public function index() {  // TODO: display_index
        $this->render('login');
    }
}

?>