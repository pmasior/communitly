<?php
require_once 'config.php';

class Database {
    private $host;
    private $database;
    private $username;
    private $password; 

    public function __construct() {
        $this->host = HOST;
        $this->database = DATABASE;
        $this->username = USERNAME;
        $this->password = PASSWORD;
    }

    public function connect() {
        try {
            $connection = new PDO(
                "pgsql:host=$this->host;port=5432;dbname=$this->database",
                $this->username,
                $this->password,
                ["sslmode" => "prefer"]
            );
            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  // wyświetlanie błędów przy łączeniu z bazą danych
            return $connection;
        } catch (PDOException $e) {
            die("Connection failed: " . $e.getMessage());
        }
    }
}

?> 