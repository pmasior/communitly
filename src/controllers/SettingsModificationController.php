<?php
require_once 'AppController.php';
require_once __DIR__ . '/../repository/GroupRepository.php';
require_once __DIR__ . '/../repository/UserRepository.php';

class SettingsModificationController extends AppController {
    private $groupRepository;
    private $userRepository;

    public function __construct() {
        parent::__construct();
        $this->groupRepository = new GroupRepository();
        $this->userRepository = new UserRepository();
    }

    public function optOutThread($threadId) {
        $userId = $_SESSION['userId'];  // TODO: safe_input
        $this->groupRepository->optOutUserFromThread($userId, $threadId);
        header('Location: /settings');
    }

    public function optInThread($threadId) {
        $userId = $_SESSION['userId'];
        $this->groupRepository->optInUserToThread($userId, $threadId);
        header('Location: /settings');
    }

//    TODO: join, quit
    public function optInGroup() {
        $userId = $_SESSION['userId'];
        $accessPassword = $_POST['accessPassword'];
        $this->groupRepository->optInUserToGroup($userId, $accessPassword);
        header('Location: /settings');
    }

    public function optOutGroup($groupId) {
        $userId = $_SESSION['userId'];
        $this->groupRepository->optOutUserFromGroup($userId, $groupId);
        header('Location: /settings');
    }

    public function optInSubgroup($subgroupId) {
        $userId = $_SESSION['userId'];
        $this->groupRepository->optInUserToSubgroup($userId, $subgroupId);
        header('Location: /settings');
    }

    public function optOutSubgroup($subgroupId) {
        $userId = $_SESSION['userId'];
        $this->groupRepository->optOutUserFromSubgroup($userId, $subgroupId);
        header('Location: /settings');
    }

    public function changeUserData() {
        if (!$this->isPost()) {
            header('Location: /');
        }

        $userId = $_SESSION['userId'];
        $realPassword = $this->userRepository->getUserForUserId($userId)->getPassword();
        $email = $_POST['email'];
        $oldPassword = $_POST['oldPassword'];
        $newPassword = $_POST['newPassword'];
        $confirmedNewPassword = $_POST['confirmedNewPassword'];
        $firstName = $_POST['firstName'];
        $lastName = $_POST['lastName'];

        $this->changeEmailIfExpected($userId, $email);
        $this->changePasswordIfExpected($userId, $realPassword, $oldPassword, $newPassword, $confirmedNewPassword);
        $this->changeFirstNameIfExpected($userId, $firstName);
        $this->changeLastNameIfExpected($userId, $lastName);
        header('Location: /logout');
    }

    private function changeEmailIfExpected($userId, $email) {
        if ($email) {
            $this->userRepository->changeEmail($userId, $email);
        }
    }

    private function changePasswordIfExpected($userId, $realPassword, $oldPassword, $newPassword, $confirmedNewPassword) {
        if ($newPassword !== $confirmedNewPassword) {
            return $this->render('settings', ['messages' => ['Passwords are different']]);
        }
        if ($oldPassword && $realPassword !== $oldPassword) {
            return $this->render('settings', ['messages' => ['Actual password is different']]);
        }
        if ($newPassword) {
            $password = password_hash($newPassword, PASSWORD_DEFAULT);
            $this->userRepository->changePassword($userId, $password);
        }
    }

    private function changeFirstNameIfExpected($userId, $firstName) {
        if ($firstName) {
            $this->userRepository->changeFirstName($userId, $firstName);
        }
    }

    private function changeLastNameIfExpected($userId, $lastName) {
        if ($lastName) {
            $this->userRepository->changeLastName($userId, $lastName);
        }
    }
}