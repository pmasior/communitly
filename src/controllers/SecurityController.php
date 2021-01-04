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

        try {
            $user = $userRepository->getUser($email);
        } catch (Exception $e) {  // TODO: change type of exception
            return $this->render('login', ['messages' => ['User with this email not exist']]);
        }
        
        if ($user->getPassword() !== $pass) {
            return $this->render('login', ['messages' => ['Wrong password']]);
        }

        return $this->render('dashboard');  // TODO: choose one
        $url = "http://$_SERVER[HTTP_HOST]";
        // header("Location: $url/dashboard");
    }
}