<?php

require_once 'Repository.php';
require_once __DIR__ . '/../models/User.php';

class UserRepository extends Repository {
    const INSERT_USER = 'SELECT insert_user(?, ?, ?, ?, ?, ?);';
    const SELECT_USER_FOR_EMAIL = 'SELECT * FROM select_user_for_email(?);';

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

    public function getUser(string $email): User {
        $queryResult = $this->select(
            self::SELECT_USER_FOR_EMAIL,
            [
                $email
            ]
        );
        if (!$queryResult) {
            throw new Exception('User with this email not exist');  // TODO: własna klasa dla wyjątku
        }
        return $this->convertDatabaseResultToObjects($queryResult, 'User')[0];
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
}
?>