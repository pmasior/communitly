<?php
require_once 'AppController.php';
require_once __DIR__ . '/../repository/UserRepository.php';

class SecurityController extends AppController {
    private $userRepository;

    public function __construct() {
        parent::__construct();
        $this->userRepository = new UserRepository();
    }

    public function login() {
        if (!$this->isPost()) {
            return $this->render('login');
        }

        try {
            $user = $this->userRepository->getUser($_POST['email']);
        } catch (Exception $e) {  // TODO: change type of exception
            return $this->render('login', ['messages' => ['User with this email not exist']]);
        }
        
        if ($user->getPassword() !== $_POST['pass']) {
            return $this->render('login', ['messages' => ['Wrong password']]);
        }

        session_start();  // TODO: delete?
        // $_SESSION['user'] = $user;
        $_SESSION['email'] = $user->getEmail();
        $_SESSION['id_users'] = $user->getUserId();
        $_SESSION['user_first_name'] = $user->getFirstname();

        header('Location: /dashboard');
    }
}