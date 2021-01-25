<?php

require_once 'Repository.php';
require_once __DIR__ . '/../models/Attachment.php';
require_once __DIR__ . '/../models/Statement.php';

class StatementRepository extends Repository {
    protected static ?Repository $uniqueInstance;

    public function __construct() {
        self::$uniqueInstance = Repository::getInstance();
    }

    public function addStatement(Statement $statement) {
        $queryResult = self::$uniqueInstance->executeAndFetch(
            'SELECT insert_statement(?, ?, ?, ?, ?);',
            [
                $statement->getHeader(),
                $statement->getContent(),
                $statement->getCreationDate()->format('c'),
                $_SESSION['userId'],
                $statement->getSourceURL()
            ]
        );
        return $queryResult['insert_statement'];
    }

    public function editStatement(Statement $statement) {
        $queryResult = self::$uniqueInstance->executeAndFetch(
            'SELECT * FROM change_statement(?, ?, ?, ?, ?, ?);',
            [
                $statement->getStatementId(),
                $statement->getHeader(),
                $statement->getContent(),
                $statement->getCreationDate()->format('c'),
                $_SESSION['userId'],
                $statement->getSourceURL()
            ]
        );
        return $queryResult['change_statement'];
    }

    public function confirmStatement($userId, $statementId, $approveDate) {
        $queryResult = self::$uniqueInstance->executeAndFetch(
            'SELECT * FROM confirm_statement(:userId, :statementId, :approveDate);',
            [$userId, $statementId, $approveDate]
        );
        return $queryResult['confirm_statement'];
    }

    public function undoConfirmStatement($userId, $statementId) {
        $queryResult = self::$uniqueInstance->executeAndFetch(
            'SELECT * FROM undo_confirm_statement(?, ?);',
            [$userId, $statementId]
        );
        return $queryResult['undo_confirm_statement'];
    }

    public function addAttachment($file, $statementId) {
        $queryResult = self::$uniqueInstance->executeAndFetch(
            'SELECT insert_attachment(?, ?);',
            [
                $file->getName(),
                $file->getType()
            ]
        );
        $attachmentId = $queryResult['insert_attachment'];

        self::$uniqueInstance->executeAndFetch(
            'SELECT associate_statement_with_attachment(?, ?);',
            [
                $statementId,
                $attachmentId
            ]
        );
        return $attachmentId;
    }

    public function associateStatementWithThread($statementId, $threadId) {
        $queryResult = self::$uniqueInstance->executeAndFetch(
            'SELECT associate_statement_with_thread(?, ?);',
            [
                $statementId,
                $threadId
            ]
        );
        return $queryResult['associate_statement_with_thread'];
    }

    public function getStatement($statementId): Statement {
        $queryResult = self::$uniqueInstance->executeAndFetchAll(
            'SELECT * FROM select_statement_for_id(?);',
            [$statementId]
        );
        $statements = $this->convertDatabaseResultToObjects($queryResult, 'Statement');
        $this->getAttachmentsForStatements($statements);
        return $statements[0];
    }

    public function getStatements($userId, $subgroupId): array {
        $queryResult = self::$uniqueInstance->executeAndFetchAll(
            'SELECT * FROM select_statements_for_user_and_subgroup(?, ?);',
            [$userId, $subgroupId]
        );
        $statements = $this->convertDatabaseResultToObjects($queryResult, 'Statement');
        $this->getAttachmentsForStatements($statements);
        return $statements;
    }

    public function getStatementsLastWeek($userId): array {
        $queryResult = self::$uniqueInstance->executeAndFetchAll(
            'SELECT * FROM select_statements_for_user_and_last_week(?);',
            [$userId]
        );
        $statements = $this->convertDatabaseResultToObjects($queryResult, 'Statement');
        $this->getAttachmentsForStatements($statements);
        return $statements;
    }

    public function removeStatement($statementId) {
        self::$uniqueInstance->executeAndFetch(
            'SELECT * FROM delete_statement(?);',
            [$statementId]
        );
    }

    public function beginTransaction() {
        self::$uniqueInstance->beginTransaction();
    }

    public function commit() {
        self::$uniqueInstance->commit();
    }

    private function getAttachmentsForStatements(array $statements) {
        foreach ($statements as $statement) {
            $queryResult = self::$uniqueInstance->executeAndFetchAll(
                'SELECT * FROM select_attachments_for_statement(?);',
                [
                    $statement->getStatementId()
                ]
            );
            $attachments = $this->convertDatabaseResultToObjects($queryResult, 'Attachment');
            foreach ($attachments as $attachment) {
                $statement->addAttachment($attachment);
            }
        }
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
        $creationUser = new User($statement['id_creation_user'], $statement['email_creation_user']);
        if ($statement['id_approve_user']) {
            $approveUser = new User($statement['id_approve_user'], $statement['email_approve_user']);
        }
        return new Statement(
            $statement['id_statements'],
            $statement['title'], 
            $statement['content'], 
            new DateTime($statement['creation_date']),
            $creationUser,
             $statement['approve_date'] ? new DateTime($statement['approve_date']) : null,
             $approveUser,
            $statement['source_url'] ?: null,
        );
    }
    
    private function convertToAttachment(array $attachment): Attachment {
        return new Attachment(
            $attachment['id_attachments'],
            $attachment['filename'],
            $attachment['type']
        );
    }
}