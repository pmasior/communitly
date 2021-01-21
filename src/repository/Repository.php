<?php
require_once __DIR__ . '/../../Database.php';

class Repository {
    protected $database;
    protected $database_connection;

    public function __construct()
    {
        $this->database = new Database();
    }

    protected function select($query, ?array $arguments = NULL) {
        if (!$this->database_connection) {
            $this->database_connection = $this->database->connect();
        }
        $stmt = $this->database_connection->prepare($query);
        $stmt->execute($arguments);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    protected function insert($query, array $arguments) {
        if (!$this->database_connection) {
            $this->database_connection = $this->database->connect();
        }
        $stmt = $this->database_connection->prepare($query);
        $stmt->execute($arguments);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    protected function update($query, array $arguments) {
        return $this->insert($query, $arguments);
    }

    // TODO: pomyśleć o tym, aby istniała tylko jedna instancja tego obiektu np. Singleton
}

?>