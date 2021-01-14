<?php
require_once 'AppController.php';
require_once __DIR__ . '/../repository/UserRepository.php';

class SecurityController extends AppController {
    private $userRepository;

    public function __construct() {
        parent::__construct();
        $this->userRepository = new UserRepository();
    }

    public function signIn() {
        if (!$this->isPost()) {
            header('Location: /');
        }

        $email = $_POST['email'];
        $password = $_POST['pass'];

        try {
            $user = $this->userRepository->getUserForEmail($email);
        } catch (Exception $e) {  // TODO: change type of exception
            return $this->render('login', ['messages' => ['User with this email not exist']]);
        }

        if (!password_verify($password, $user->getPassword())) {
            return $this->render('login', ['messages' => ['Wrong password']]);
        }

        $permissions = $this->userRepository->getPermissions($user->getUserId());

        $_SESSION['email'] = $user->getEmail();
        $_SESSION['userId'] = $user->getUserId();
        $_SESSION['userFirstName'] = $user->getFirstname();
        $_SESSION['userLastName'] = $user->getLastname();
        $_SESSION['permissions'] = $permissions;
        header('Location: /dashboard');
    }

    public function signUp() {
        if (!$this->isPost()) {
            header('Location: /');
        }

        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirmedPassword = $_POST['confirmedPassword'];
        $firstName = $_POST['firstName'];
        $lastName = $_POST['lastName'];

        if ($password !== $confirmedPassword) {
            return $this->render('register', ['messages' => ['Passwords are different']]);
        }

        $password = password_hash($password, PASSWORD_DEFAULT);
        $user = new User(
            NULL,
            $email,
            $password,
            $firstName,
            $lastName
        );

        $this->userRepository->addUser($user);
        header('Location: /');
    }

    public function logout() {
        session_unset();
        session_destroy();
        return $this->render('login', ['messages' => ['You have been successfully logged out']]);
    }
}