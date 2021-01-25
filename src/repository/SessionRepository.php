<?php
require_once 'Repository.php';

class SessionRepository extends Repository {
    protected static ?Repository $uniqueInstance;

    public function __construct() {
        self::$uniqueInstance = Repository::getInstance();
    }

    public function getUserIdForAutoLoginId($autoLoginId): ?int {
        $queryResult = self::$uniqueInstance->executeAndFetchAll(
            'SELECT * FROM select_user_id_for_auto_login_id(:autoLoginId);',
            [$autoLoginId]
        );
        return $queryResult[0]['id_users'];
    }

    public function setAutoLoginForUserId($userId, $autoLoginId) {
        $queryResult = self::$uniqueInstance->executeAndFetch(
            'SELECT insert_auto_login(?, ?, ?);',
            [
                $userId,
                $autoLoginId,
                (new DateTime())->format('c')
            ]
        );
        return $queryResult['insert_auto_login'];
    }

    public function updateAutoLogin($userId, $autoLoginId) {
        $queryResult = self::$uniqueInstance->executeAndFetch(
            'SELECT * FROM update_auto_login(?, ?, ?);',
            [
                $userId,
                $autoLoginId,
                (new DateTime())->format('c')
            ]
        );
        return $queryResult['update_auto_login'];
    }
}