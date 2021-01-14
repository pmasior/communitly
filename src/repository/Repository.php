<?php
require_once __DIR__ . '/../../Database.php';

class Repository {
    protected $database;

    public function __construct()  {
        $this->database = new Database();
    }

    protected function select($query, ?array $arguments = NULL) {
        $stmt = $this->database->connect()->prepare($query);
        if ($arguments) {
            $stmt->execute($arguments);
        } else {
            $stmt->execute();
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    protected function insert($query, array $arguments) {
        $stmt = $this->database->connect()->prepare($query);
        $stmt->execute($arguments);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    protected function update($query, array $arguments) {
        return $this->insert($query, $arguments);
    }

    // TODO: pomyśleć o tym, aby istniała tylko jedna instancja tego obiektu np. Singleton
}

?>