<?php
require_once __DIR__ . '/../../Database.php';

class Repository {
    protected $database;

    public function __construct()  {
        $this->database = new Database();
    }

    // TODO: pomyśleć o tym, aby istniała tylko jedna instancja tego obiektu np. Singleton
}

?>