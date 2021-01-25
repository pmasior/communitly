<?php
require_once 'AppController.php';
require_once __DIR__ . '/../exceptions/NotFoundUserException.php';
require_once __DIR__ . '/../repository/UserRepository.php';

class SecurityController extends AppController {
    private UserRepository $userRepository;

    public function __construct() {
        parent::__construct();
        $this->userRepository = new UserRepository();
    }

    public function signIn():void {
        (new Session())->handleSession(false);
        if (!$this->isPost()) {
            header('Location: /');
        }

        $email = $_POST['email'];
        $password = $_POST['password'];

        $this->userRepository->beginTransaction();
        try {
            $user = $this->userRepository->getUserForEmail($email);
        } catch (NotFoundUserException $e) {
            $this->render('login', ['messages' => ['User with this email not exist']]);
            return;
        }

        if (!password_verify($password, $user->getPassword())) {
            $this->render('login', ['messages' => ['Wrong password']]);
            return;
        }

        $permissions = $this->userRepository->getPermissions($user->getUserId());
        $this->userRepository->commit();

        $_SESSION['email'] = $user->getEmail();
        $_SESSION['userId'] = $user->getUserId();
        $_SESSION['userFirstName'] = $user->getFirstname();
        $_SESSION['userLastName'] = $user->getLastname();
        $_SESSION['permissions'] = $permissions;

        (new AutoLogin())->setAutoLogin($_SESSION['userId']);

        header('Location: /dashboard');
    }

    public function signUp(): void {
        (new Session())->handleSession(false);
        if (!$this->isPost()) {
            header('Location: /');
        }

        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirmedPassword = $_POST['confirmedPassword'];
        $firstName = $_POST['firstName'];
        $lastName = $_POST['lastName'];

        if ($password !== $confirmedPassword) {
            $this->render('register', ['messages' => ['Passwords are different']]);
            return;
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

    public function logout(): void {
        (new Session())->handleSession(false);
        session_unset();
        session_destroy();
        $this->render('login', ['messages' => ['You have been successfully logged out']]);
    }
}