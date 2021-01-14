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
                $_SESSION['userId'],
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

    public function getStatements($userId, $subgroupId): array {
        $queryResult = $this->select(
            self::SELECT_STATEMENTS_FOR_USER_AND_SUBGROUP,
            [
                $userId, 
                $subgroupId
            ]
        );
        $statements = $this->convertDatabaseResultToObjects($queryResult, 'Statement');
        foreach ($statements as $statement) {
            // TODO: przyspieszyć/zmiejszyć liczbę zapytań do bazy
                // tablica z kluczami i wartościami dla statements
                // $tablicaResult = SELECT attachments, statements FROM ... WHERE id_attachments IN (SELECT statements for user)
                // przypisanie każdego attachments z $tablicaResult do odpowiedniego statement
            $queryResult = $this->select(
                self::SELECT_ATTACHMENTS_FOR_STATEMENT,
                [
                    $statement->getStatementId()
                ]
            );
            $attachments = $this->convertDatabaseResultToObjects($queryResult, 'Attachment');
            foreach ($attachments as $attachment) {
                $statement->addAttachment($attachment);
            }
        }
        return $statements;
    }

    private function convertDatabaseResultToObjects(array $records, string $convertTo): array {
        $convert = 'convertTo' . $convertTo;
        $result = [];
        foreach ($records as $record) {
            $result[] = $this->$convert($record);
        }
        return $result;
    }
    
    private function convertToStatement(array $statement): Statement {
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
    
    private function convertToAttachment(array $attachment): Attachment {
        return new Attachment(
            $attachment['id_attachments'],
            $attachment['filename'], 
            $attachment['server_filename'], 
            $attachment['type']
        );
    }
}
?>