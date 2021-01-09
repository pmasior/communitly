<?php

require_once 'Repository.php';
require_once __DIR__ . '/../models/Attachment.php';
require_once __DIR__ . '/../models/Statement.php';

class StatementRepository extends Repository {
    const INSERT_STATEMENT = 'SELECT insert_statement(?, ?, ?, ?, ?);';
    const INSERT_ATTACHMENT = 'SELECT insert_attachment(?, ?, ?);';
    const ASSOCIATE_STATEMENT_WITH_ATTACHMENT = 'SELECT associate_statement_with_attachment(?, ?);';
    const SELECT_ATTACHMENTS_FOR_STATEMENT = 'SELECT * FROM select_attachments_for_statement(?);';
    const ASSOCIATE_STATEMENT_WITH_THREAD = 'SELECT associate_statement_with_thread(?, ?);';
    const SELECT_STATEMENTS_FOR_USER_AND_SUBGROUP = 'SELECT * FROM select_statements_for_user_and_subgroup(?, ?);';

    public function addStatement(Statement $statement) {
        $queryResult = $this->insert(
            self::INSERT_STATEMENT, 
            [
                $statement->getHeader(),  // TODO: fix empty string
                $statement->getContent(),
                $statement->getCreationDate()->format('d.m.Y H:i:s'),
                $_SESSION['id_users'],
                $statement->getSourceURL()
            ]
            );
        return $queryResult['insert_statement'];
        // TODO: approve_date, id_approve_user
    }

    public function addAttachment($file, $statementId) {
        $queryResult = $this->insert(
            self::INSERT_ATTACHMENT,
            [
                $file->getName(),
                $file->getName(), //TODO: zmienić
                $file->getType()
            ]
        );
        $attachmentId = $queryResult['insert_attachment'];

        $this->insert(
            self::ASSOCIATE_STATEMENT_WITH_ATTACHMENT,
            [
                $statementId,
                $attachmentId
            ]
            );
        return $attachmentId;
    }

    public function associateStatementWithThread($statementId, $threadId) {
        $queryResult = $this->insert(
            self::ASSOCIATE_STATEMENT_WITH_THREAD,
            [
                $statementId,
                $threadId
            ]
        );
        return $queryResult['associate_statement_with_thread'];
    }

    public function getStatement(string $idStatement): ?User {
        $statement = $this->selectStatementFromDatabase($idStatement);
        if (!$statement) {
            throw new Exception('Statement with this id not exist');  // TODO: własna klasa dla wyjątku
        }
        return $this->convertDatabaseResultToStatement($statement);
    }

    public function getStatements(?string $userId=NULL, ?string $subgroupId=NULL): array {  //TODO: usunąć tymczasową zgodność
        // if (!$userId && !$subgroupId) {
        //     $statements = $this->selectStatementsFromDatabase();
        //     return $this->convertDatabaseResultToStatements($statements);
        // }
        // $queryResult = $this->selectStatementsFromDatabase($userId, $subgroupId);
        $queryResult = $this->select(
            self::SELECT_STATEMENTS_FOR_USER_AND_SUBGROUP,
            [
                $userId, 
                $subgroupId
            ]
            );
        $statements = $this->convertDatabaseResultToStatements($queryResult);
        foreach ($statements as $statement) {
            $queryResult = $this->select(
                self::SELECT_ATTACHMENTS_FOR_STATEMENT,
                [
                    $statement->getStatementId()
                ]
            );
            $attachments = $this->convertDatabaseResultToAttachments($queryResult);
            foreach ($attachments as $attachment) {
                $statement->addAttachment($attachment);
            }
        }
        return $statements;
    }

    private function selectStatementsFromDatabase(?string $userId=NULL, ?string $subgroupId=NULL): array {  //TODO: usunąć tymczasową zgodność
        $query = '
            -- lista komunikatów dla wątków, do których należy użytkownik
            SELECT *
            FROM public.statements
                INNER JOIN public.statements_threads
                    USING (id_statements)
                INNER JOIN public.threads
                    USING (id_threads)
            WHERE id_threads IN
                (SELECT DISTINCT users_threads.id_threads
                FROM public.threads
                    INNER JOIN public.users_threads
                        USING (id_threads)
                    INNER JOIN public.users
                        USING (id_users)
                WHERE id_subgroups=:id_subgroups AND id_users=:id_users);
        ';
        $stmt = $this->database->connect()->prepare($query);
        $stmt->bindParam(':id_subgroups', $subgroupId, PDO::PARAM_INT);
        $stmt->bindParam(':id_users', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function selectStatementFromDatabase(string $idStatement): ?array {
        $statement = $this->database->connect()->prepare('
            SELECT * FROM public.statements WHERE id_statements = :id_statements
        ');
        $statement->bindParam(':id_statements', $idStatement, PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    // private function selectStatementsFromDatabase() {
    //     $stmt = $this->database->connect()->prepare('
    //         SELECT * FROM public.statements;
    //     ');
    //     $stmt->execute();
    //     return $stmt->fetchAll(PDO::FETCH_ASSOC);
    // }


    private function convertDatabaseResultToStatements(array $statements): array {
        $result = [];
        foreach ($statements as $statement) {
            $result[] = $this->convertDatabaseResultToStatement($statement);
        }
        return $result;
    }
    
    private function convertDatabaseResultToStatement(array $statement): Statement {
        // print_r($statement);
        // throw new Exception('debug');
        return new Statement(
            $statement['id_statements'],
            $statement['title'], 
            $statement['content'], 
            new DateTime($statement['creation_date']),  // TODO: zmienić
            $statement['id_creation_user'],
            // $statement['approve_date'] ? new DateTime($statement['approve_date']) : null,
            // $statement['id_approve_user'],
            // ' ',  // TODO: attachments
            $statement['source_url'] ?: null,
        );
    }


    private function convertDatabaseResultToAttachments(array $attachments): array {
        $result = [];
        foreach ($attachments as $attachment) {
            $result[] = $this->convertDatabaseResultToAttachment($attachment);
        }
        return $result;
    }
    
    private function convertDatabaseResultToAttachment(array $attachment): Attachment {
        return new Attachment(
            $attachment['id_attachments'],
            $attachment['filename'], 
            $attachment['server_filename'], 
            $attachment['type']
        );
    }
}

?>