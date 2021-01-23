<?php
require_once 'AppController.php';
require_once __DIR__ . '/../repository/GroupRepository.php';

class GroupController extends AppController {
    private GroupRepository $groupRepository;

    public function __construct() {
        parent::__construct();
        $this->groupRepository = new GroupRepository();
    }

    public function createGroup() {
        (new Session())->handleSession(true);
        $fullName = $_POST['fullName'];
        $shortName = $_POST['shortName'];
        $accessPassword = $this->generateRandomAccessPassword();

        $group = new Group (
            NULL,
            $fullName,
            $shortName,
            $accessPassword
        );
        $this->groupRepository->beginTransaction();
        $groupId = $this->groupRepository->createGroup($group);
        $this->groupRepository->setAdminForGroup($groupId, $_SESSION['userId']);
        $this->groupRepository->commit();

        (new SettingsController())->settings(['Utworzyłeś grupę ' . $group->getFullName() . '. Hasło zapisu do grupy to: ' . $accessPassword]);
    }

    public function createSubgroup() {
        (new Session())->handleSession(true);
        $groupId = $_POST['groupId'];
        $fullName = $_POST['fullName'];
        $shortName = $_POST['shortName'];

        $subgroup = new Subgroup(
            NULL,
            $fullName,
            $shortName
        );

        $subgroupId = $this->groupRepository->createSubgroup($subgroup, $groupId);
        header("Location: /settings");
    }

    public function createThread() {
        (new Session())->handleSession(true);
        $subgroupId = $_POST['subgroupId'];
        $name = $_POST['name'];

        $thread = new Thread(
            NULL,
            $subgroupId,
            $name
        );

        $threadId = $this->groupRepository->createThread($thread);
        header("Location: /settings");
    }

    public function deleteGroup() {
        (new Session())->handleSession(true);
        $groupId = $_POST['groupId'];

        if ($_SESSION['permissions'][$groupId] == 1) {
            $this->groupRepository->deleteGroup($groupId);
            (new SettingsController())->settings(['Usunąłeś grupę']);
        }
    }

    public function deleteSubgroup() {
        (new Session())->handleSession(true);
        $groupId = $_POST['groupId'];
        $subgroupId = $_POST['subgroupId'];

        if ($_SESSION['permissions'][$groupId] == 1) {
            $this->groupRepository->deleteSubgroup($subgroupId);
            (new SettingsController())->settings(['Usunąłeś podgrupę']);
        }
    }

    public function deleteThread() {
        (new Session())->handleSession(true);
        $groupId = $_POST['groupId'];
        $threadId = $_POST['threadId'];

        if ($_SESSION['permissions'][$groupId] == 1) {
            $this->groupRepository->deleteThread($threadId);
            (new SettingsController())->settings(['Usunąłeś wątek']);
        }
    }

    private function generateRandomAccessPassword(): string {
        $passwordLength = 10;
        $availableChars = '23456789qwertyuiopasdfghjkzxcvbnmQWERTYUIPASDFGHJKLZXCVBNM';
        $accessPassword = '';

        for ($i = 0; $i < $passwordLength; $i++) {
            $accessPassword .= $availableChars[random_int(0, strlen($availableChars-1))];
        }

        return $accessPassword;
    }
}