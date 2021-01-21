<?php
require_once 'Repository.php';

class SessionRepository extends Repository {
    const INSERT_AUTO_LOGIN = 'SELECT insert_auto_login(?, ?, ?);';
    const UPDATE_AUTO_LOGIN = 'SELECT * FROM update_auto_login(?, ?, ?);';


    public function getUserIdForAutoLoginId($autoLoginId): ?int {
        $queryResult = $this->select(
            'SELECT * FROM select_user_id_for_auto_login_id(:autoLoginId);',
            [$autoLoginId]
        );
        return $queryResult[0]['id_users'];
//        $subgroupsArray = $this->convertDatabaseResultToObjects($subgroupQueryResult, 'Subgroup');
//        return $subgroupsArray[0];
    }

    public function setAutoLoginForUserId($userId, $autoLoginId) {
        $queryResult = $this->insert(
            self::INSERT_AUTO_LOGIN,
            [
                $userId,
                $autoLoginId,
                (new DateTime())->format('c')
            ]
        );
        return $queryResult['insert_auto_login'];
    }

    public function updateAutoLogin($userId, $autoLoginId) {
        $queryResult = $this->update(
            self::UPDATE_AUTO_LOGIN,
            [
                $userId,
                $autoLoginId,
                (new DateTime())->format('c')
            ]
        );
        return $queryResult['update_auto_login'];
    }

//    TODO: delete old AutoLogin
}