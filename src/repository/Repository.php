<?php
require_once __DIR__ . '/../../Database.php';

class Repository {
    protected static ?Repository $uniqueInstance = null;
    protected Database $database;
    protected $database_connection;

    private function __construct() {
        $this->database = new Database();
    }

    public static function getInstance(): Repository {
        if (self::$uniqueInstance == null) {
            self::$uniqueInstance = new Repository();
        }
        return self::$uniqueInstance;
    }

    protected function executeAndFetchAll($query, ?array $arguments = NULL): array {
        $stmt = $this->execute($query, $arguments);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    protected function executeAndFetch($query, ?array $arguments = NULL) {
        $stmt = $this->execute($query, $arguments);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    private function execute($query, ?array $arguments = NULL) {
        if (!$this->database_connection) {
            $this->database_connection = $this->database->connect();
        }
        $stmt = $this->database_connection->prepare($query);
        $stmt->execute($arguments);
        return $stmt;
    }
}