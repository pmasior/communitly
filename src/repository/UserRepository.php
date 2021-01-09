<?php

require_once 'Repository.php';
require_once __DIR__ . '/../models/User.php';

class UserRepository extends Repository {

    public function getUser(string $email): User {
        $user = $this->selectUserFromDatabase($email);
        if (!$user) {
            throw new Exception('User with this email not exist');  // TODO: własna klasa dla wyjątku
        }
        $userDetails = $this->selectUserDetailsFromDataBase($user['id_users']);
        return $this->convertDatabaseResultToUser($user, $userDetails);
    }

    private function selectUserFromDatabase(string $email) {
        $stmt = $this->database->connect()->prepare('
            SELECT * FROM public.users WHERE email = :email
        ');
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    private function selectUserDetailsFromDataBase(string $userId) {
        $stmt = $this->database->connect()->prepare('
            SELECT * 
            FROM public.users_details 
                INNER JOIN public.users
                    USING (id_users_details)
            WHERE id_users = :id_users
        ');
        $stmt->bindParam(':id_users', $userId, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    private function convertDatabaseResultToUser(array $user, array $userDetails): User {
        return new User(
            $user['id_users'],
            $user['email'], 
            $user['password'],
            $userDetails['first_name'],
            $userDetails['last_name']
        );
    }
}

?>