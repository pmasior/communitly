<?php
require_once 'AppController.php';
require_once __DIR__ . '/../repository/GroupRepository.php';
require_once __DIR__ . '/../repository/UserRepository.php';

class SettingsController extends AppController {
    private $groupRepository;

    public function __construct() {
        parent::__construct();
        $this->groupRepository = new GroupRepository();
    }

    public function settings() {
        $userFirstname = $_SESSION['userFirstName'];
        $userLastName = $_SESSION['userLastName'];
        $userEmail = $_SESSION['email'];
        $userPermissions = $_SESSION['permissions'];
        $groupsInMenu = $this->groupRepository->getGroups($_SESSION['userId'], true, true, false);
        $availableGroups = $this->groupRepository->getGroups($_SESSION['userId'], true, true, true);
        $groupsIdForUser = $this->getGroupsIdForUser($groupsInMenu);
        $subgroupsIdForUser = $this->getSubgroupsIdForUser($groupsInMenu);
        $threadsIdForUser = $this->getThreadsIdForUser($groupsInMenu);

        $this->render('settings', [
            'userFirstname' => $userFirstname,
            'userLastName' => $userLastName,
            'userEmail' => $userEmail,
            'userPermissions' => $userPermissions,
            'groups' => $groupsInMenu,
            'availableGroups' => $availableGroups,
            'groupsIdForUser' => $groupsIdForUser,
            'subgroupsIdForUser' => $subgroupsIdForUser,
            'threadsIdForUser' => $threadsIdForUser
        ]);
    }

    private function getGroupsIdForUser($groups) {
        $result = [];
        foreach ($groups as $group) {
            $result[] = $group->getGroupId();
        }
        return $result;
    }

    private function getSubgroupsIdForUser($groups) {
        $result = [];
        foreach ($groups as $group) {
            foreach ($group->getSubgroups() as $subgroup) {
                $result[] = $subgroup->getSubgroupId();
            }
        }
        return $result;
    }

    private function getThreadsIdForUser($groups) {
        $result = [];
        foreach ($groups as $group) {
            foreach ($group->getSubgroups() as $subgroup) {
                foreach ($subgroup->getThreads() as $thread) {
                    $result[] = $thread->getThreadId();
                }
            }
        }
        return $result;
    }
}
?>