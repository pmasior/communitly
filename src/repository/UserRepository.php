<?php

require_once 'Repository.php';
require_once __DIR__ . '/../models/User.php';

class UserRepository extends Repository {
    const INSERT_USER = 'SELECT insert_user(?, ?, ?, ?, ?, ?);';
    const SELECT_USER_FOR_EMAIL = 'SELECT * FROM select_user_for_email(?);';
    const SELECT_USER_FOR_USER_ID = 'SELECT * FROM select_user_for_user_id(?);';
    const CHANGE_EMAIL_FOR_USER_ID = 'SELECT * FROM change_email_for_user_id(?, ?);';
    const CHANGE_PASSWORD_FOR_USER_ID = 'SELECT * FROM change_password_for_user_id(?, ?);';
    const CHANGE_FIRST_NAME_FOR_USER_ID = 'SELECT * FROM change_first_name_for_user_id(?, ?);';
    const CHANGE_LAST_NAME_FOR_USER_ID = 'SELECT * FROM change_last_name_for_user_id(?, ?);';
    const SELECT_PERMISSIONS_FOR_USER = 'SELECT * FROM select_permissions_for_user(?);';

    public function addUser(User $user) {
        $queryResult = $this->insert(
            self::INSERT_USER,
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
        $queryResult = $this->select(
            self::SELECT_USER_FOR_USER_ID,
            [$userId]
        );
        return $this->convertDatabaseResultToObjects($queryResult, 'User')[0];
    }

    public function getUserForEmail(string $email): User {
        $queryResult = $this->select(
            self::SELECT_USER_FOR_EMAIL,
            [$email]
        );
        if (!$queryResult) {
            throw new Exception('User with this email not exist');  // TODO: własna klasa dla wyjątku
        }
        return $this->convertDatabaseResultToObjects($queryResult, 'User')[0];
    }

    public function changeEmail($userId, $email) {
        $queryResult = $this->update(
            self::CHANGE_EMAIL_FOR_USER_ID,
            [$userId, $email]
        );
        return $queryResult['change_email_for_user_id'];
    }

    public function changePassword($userId, $newPassword) {
        $queryResult = $this->update(
            self::CHANGE_PASSWORD_FOR_USER_ID,
            [$userId, $newPassword]
        );
        return $queryResult['change_password_for_user_id'];
    }

    public function changeFirstName($userId, $firstName) {
        $queryResult = $this->update(
            self::CHANGE_FIRST_NAME_FOR_USER_ID,
            [(int)$userId, $firstName]
        );
        return $queryResult['change_first_name_for_user_id'];

    }

    public function changeLastName($userId, $lastName) {
        $queryResult = $this->update(
            self::CHANGE_LAST_NAME_FOR_USER_ID,
            [$userId, $lastName]
        );
        return $queryResult['change_last_name_for_user_id'];
    }

    public function getPermissions($userId) {
        $queryResult = $this->select(
            self::SELECT_PERMISSIONS_FOR_USER,
            [$userId]
        );
        return $this->convertToPermissionArray($queryResult);
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
?>