<?php
require_once 'AppController.php';
require_once __DIR__ . '/../repository/UserRepository.php';

class SecurityController extends AppController {
    public function login() {
        $userRepository = new UserRepository();

        if (!$this->isPost()) {
            return $this->render('login');
        }
        $email = $_POST['login'];
        $pass = $_POST['pass'];

        $user = $userRepository->getUser($email);
        if (!$user) {
            return $this->render('login', ['messages' => ['User with this email not exist']]);
        }
        
        if ($user->getEmail() !== $email) {
            return $this->render('login', ['messages' => ['User with this email not exist']]);
        }
        // TODO: usuń niepotrzebne
        
        if ($user->getPassword() !== $pass) {
            return $this->render('login', ['messages' => ['Wrong password']]);
        }

        return $this->render('dashboard');  // TODO: choose one
        $url = "http://$_SERVER[HTTP_HOST]";
        // header("Location: $url/dashboard");
    }
}