<?php

require_once 'Repository.php';
require_once __DIR__ . '/../exceptions/NotFoundUserException.php';
require_once __DIR__ . '/../models/User.php';

class UserRepository extends Repository {
    protected static ?Repository $uniqueInstance;

    public function __construct() {
        self::$uniqueInstance = Repository::getInstance();
    }

    public function addUser(User $user) {
        $queryResult = self::$uniqueInstance->executeAndFetch(
            'SELECT insert_user(?, ?, ?, ?, ?, ?);',
            [
                $user->getEmail(),
                $user->getPassword(),
                true,
                (new DateTime())->format('Y-m-d'),
                $user->getFirstname(),
                $user->getLastname()
            ]
        );
        return $queryResult['insert_user'];
    }

    public function getUserForUserId($userId) {
        $queryResult = self::$uniqueInstance->executeAndFetchAll(
            'SELECT * FROM select_user_for_user_id(?);',
            [$userId]
        );
        return $this->convertDatabaseResultToObjects($queryResult, 'User')[0];
    }

    public function getUserForEmail(string $email): User {
        $queryResult = self::$uniqueInstance->executeAndFetchAll(
            'SELECT * FROM select_user_for_email(?);',
            [$email]
        );
        if (!$queryResult) {
            throw new NotFoundUserException();
        }
        return $this->convertDatabaseResultToObjects($queryResult, 'User')[0];
    }

    public function changeEmail($userId, $email) {
        $queryResult = self::$uniqueInstance->executeAndFetch(
            'SELECT * FROM change_email_for_user_id(?, ?);',
            [$userId, $email]
        );
        return $queryResult['change_email_for_user_id'];
    }

    public function changePassword($userId, $newPassword) {
        $queryResult = self::$uniqueInstance->executeAndFetch(
            'SELECT * FROM change_password_for_user_id(?, ?);',
            [$userId, $newPassword]
        );
        return $queryResult['change_password_for_user_id'];
    }

    public function changeFirstName($userId, $firstName) {
        $queryResult = self::$uniqueInstance->executeAndFetch(
            'SELECT * FROM change_first_name_for_user_id(?, ?);',
            [(int)$userId, $firstName]
        );
        return $queryResult['change_first_name_for_user_id'];

    }

    public function changeLastName($userId, $lastName) {
        $queryResult = self::$uniqueInstance->executeAndFetch(
            'SELECT * FROM change_last_name_for_user_id(?, ?);',
            [$userId, $lastName]
        );
        return $queryResult['change_last_name_for_user_id'];
    }

    public function getPermissions($userId): array {
        $queryResult = self::$uniqueInstance->executeAndFetchAll(
            'SELECT * FROM select_permissions_for_user(?);',
            [$userId]
        );
        return $this->convertToPermissionArray($queryResult);
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
    
    private function convertToUser(array $queryResult): User {
        return new User(
            $queryResult['id_users'],
            $queryResult['email'],
            $queryResult['password'],
            $queryResult['first_name'],
            $queryResult['last_name']
        );
    }

    private function convertToPermissionArray(array $queryResult): array {
        $result = [];
        foreach ($queryResult as $permission) {
            $result[$permission['id_groups']] = $permission['id_users_types'];
        }
        return $result;
    }
}