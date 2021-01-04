<?php

require_once 'Repository.php';
require_once __DIR__ . '/../models/User.php';

class UserRepository extends Repository {

    public function getUser(string $email): ?User {
        $user = $this->fetchUserFromDatabase($email);
        if (!$user) {
            throw new Exception('User with this email not exist');  // TODO: własna klasa dla wyjątku
        }
        return $this->convertDatabaseResultToUser($user);
    }

    private function fetchUserFromDatabase(string $email) {
        $statement = $this->database->connect()->prepare('
            SELECT * FROM public.users WHERE email = :email
        ');
        $statement->bindParam(':email', $email, PDO::PARAM_STR);
        $statement->execute();

        return $statement->fetch(PDO::FETCH_ASSOC);
    }
    
    private function convertDatabaseResultToUser(array $user): User {
        return new User(
            $user['email'], 
            $user['password'],
            ' ', ' '
        );
    }
}

?>