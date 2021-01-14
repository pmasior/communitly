<?php


class GroupController extends AppController {
    private $groupRepository;

    public function __construct() {
        parent::__construct();
        $this->groupRepository = new GroupRepository();
    }

    public function createGroup() {
        $fullName = $_POST['fullName'];
        $shortName = $_POST['shortName'];
        $accessPassword = $this->generateRandomAccessPassword();
//        $hashedAccessPassword = password_hash($accessPassword, PASSWORD_DEFAULT);

        $group = new Group (
            NULL,
            $fullName,
            $shortName,
            $accessPassword
        );
        $groupId = $this->groupRepository->createGroup($group);

        $this->render(
            'settings',
            ['messages' =>
                ['Utworzyłeś grupę ' . $group->getFullName() . '. Hasło zapisu do grupy to: ' . $accessPassword]
            ]);
        return;
    }

    public function createSubgroup() {
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

    private function generateRandomAccessPassword(): string {
        $passwordLength = 10;
        $availableChars = '1234567890qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM';
        $accessPassword = '';

        for ($i = 0; $i < $passwordLength; $i++) {
            $accessPassword .= $availableChars[random_int(0, strlen($availableChars-1))];
        }

        return $accessPassword;
    }
}