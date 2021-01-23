<?php

require_once __DIR__ . '/../session/AutoLogin.php';
require_once __DIR__ . '/../repository/UserRepository.php';

class Session {

    public function handleSession($redirectIfNotLogged) {
        session_start();
        (new AutoLogin())->verifySession($redirectIfNotLogged);
    }

    public static function setSessionVariablesForUser($userId) {
        if (!isset($_SESSION['userId']) && $userId) {
            $userRepository = new UserRepository();
            $user = $userRepository->getUserForUserId($userId);
            $_SESSION['email'] = $user->getEmail();
            $_SESSION['userId'] = $user->getUserId();
            $_SESSION['userFirstName'] = $user->getFirstname();
            $_SESSION['userLastName'] = $user->getLastname();
            $permissions = $userRepository->getPermissions($user->getUserId());
            $_SESSION['permissions'] = $permissions;
        }
    }
}