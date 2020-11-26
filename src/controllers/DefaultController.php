<?php
require_once 'AppController.php';

class DefaultController extends AppController {

    public function index() {  // TODO: display_index
        $this->render('login');
    }

    public function dashboard() {  // TODO: display_dashboard
        $this->render('dashboard');
    }

    public function wdpai() {  // TODO: display_dashboard
        $this->render('wdpai');
    }
}

?>