<?php

require_once 'Repository.php';
require_once __DIR__ . '/../models/Statement.php';

class StatementRepository extends Repository {

    public function addStatement(Statement $statement) {
        $this->addStatementToDatabase($statement);
    }

    private function addStatementToDatabase(Statement $statement) {
        $stmt = $this->database->connect()->prepare('
            INSERT INTO public.statements 
                (title, content, creation_date, id_creation_user)
            VALUES
                (?, ?, ?, ?);
        ');
        $date = new DateTime();
        $stmt->execute([
            $statement->getHeader(),
            $statement->getContent(),
            $date->format('Y-m-d H:i:s O'),  // TODO: zmienić
            1 // TODO: zmienić
        ]);
        // throw new Exception('tu jestem');  // TODO: debug
    }

    public function getStatement(string $idStatement): ?User {
        $statement = $this->fetchStatementFromDatabase($idStatement);
        if (!$statement) {
            throw new Exception('Statement with this id not exist');  // TODO: własna klasa dla wyjątku
        }
        return $this->convertDatabaseResultToStatement($statement);
    }

    private function fetchStatementFromDatabase(string $idStatement) {
        $statement = $this->database->connect()->prepare('
            SELECT * FROM public.statements WHERE id_statements = :id_statements
        ');
        $statement->bindParam(':id_statements', $idStatement, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetch(PDO::FETCH_ASSOC);
    }
    
    private function convertDatabaseResultToStatement(array $statement): User {
        return new Statement(
            $statement['title'], 
            $statement['content'], 
            ' '
        );
    }
}

?>