<?php

require_once 'Repository.php';
require_once __DIR__ . '/../models/Attachment.php';
require_once __DIR__ . '/../models/Statement.php';

class LinkRepository extends Repository {
    protected static ?Repository $uniqueInstance;

    public function __construct() {
        self::$uniqueInstance = Repository::getInstance();
    }

    public function addLink(Link $link) {
        $queryResult = self::$uniqueInstance->executeAndFetch(
            'SELECT insert_link(?, ?, ?);',
            [
                $link->getTitle(),
                $link->getUrl(),
                $link->getNote()
            ]
        );
        return $queryResult['insert_link'];
    }

    public function associateLinkWithThread($linkId, $threadId) {
        self::$uniqueInstance->executeAndFetch(
            'SELECT associate_link_with_thread(?, ?);',
            [$linkId, $threadId]
        );
    }

    public function getLinks($userId, $subgroupId): array {
        $queryResult = self::$uniqueInstance->executeAndFetchAll(
            'SELECT * FROM select_links_for_user_and_subgroup(?, ?);',
            [$userId, $subgroupId]
        );
        return $this->convertDatabaseResultToObjects($queryResult, 'Link');
    }

    public function removeLink($linkId) {
        self::$uniqueInstance->executeAndFetch(
            'SELECT * FROM delete_links(?);',
            [$linkId]
        );
    }

    public function beginTransaction() {
        self::$uniqueInstance->beginTransaction();
    }

    public function commit() {
        self::$uniqueInstance->commit();
    }

    private function convertDatabaseResultToObjects(array $records, string $convertTo): array {
        $convert = 'convertTo' . $convertTo;
        $result = [];
        foreach ($records as $record) {
            $result[] = $this->$convert($record);
        }
        return $result;
    }
    
    private function convertToLink(array $link): Link {
        return new Link(
            $link['id_links'],
            $link['title'],
            $link['url'],
            $link['note']
        );
    }
}