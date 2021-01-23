<?php
require_once 'AppController.php';
require_once __DIR__ . '/../repository/GroupRepository.php';
require_once __DIR__ . '/../repository/StatementRepository.php';
require_once __DIR__ . '/../repository/UserRepository.php';

class SubgroupController extends AppController {
    private LinkRepository $linkRepository;
    private GroupRepository $groupRepository;
    private StatementRepository $statementRepository;

    public function __construct() {
        parent::__construct();
        $this->linkRepository = new LinkRepository();
        $this->groupRepository = new GroupRepository();
        $this->statementRepository = new StatementRepository();
    }

    public function subgroup($subgroupId) {
        (new Session())->handleSession(true);
        $groupsInMenu = $this->groupRepository->getGroups($_SESSION['userId'], true, false, false);
        $displayedSubgroup = $this->groupRepository->getSubgroup($_SESSION['userId'], $subgroupId);
        if (!$displayedSubgroup) {
            header('Location: /dashboard');
        }
        $statements = $this->statementRepository->getStatements($_SESSION['userId'], $subgroupId);
        $links = $this->linkRepository->getLinks($_SESSION['userId'], $subgroupId);
        $allThreadsInSubgroup = $this->groupRepository->getThreads($subgroupId);
        $groupId = $this->getGroupId($groupsInMenu, $subgroupId);
        $permission = $_SESSION['permissions'][$groupId];
        $activeThreads = $this->groupRepository->getThreads($subgroupId, $_SESSION['userId']);

        $this->render('subgroup', [
            'groups' => $groupsInMenu, 
            'statements' => $statements,
            'links' => $links,
            'openSubgroup' => $displayedSubgroup,
            'id' => $subgroupId,
            'allThreadsInSubgroup' => $allThreadsInSubgroup,
            'permission' => $permission,
            'activeThreads' => $activeThreads
        ]);
    }

    public function dashboard($messages = NULL) {
        (new Session())->handleSession(true);
        $userFirstname = $_SESSION['userFirstName'];
        $groupsInMenu = $this->groupRepository->getGroups($_SESSION['userId'], true, false, false);
        $statements = $this->statementRepository->getStatementsLastWeek($_SESSION['userId']);
        
        $this->render('dashboard', [
            'userFirstname' => $userFirstname,
            'groups' => $groupsInMenu,
            'statements' => $statements,
            'messages' => $messages
        ]);
    }

    private function getGroupId($groups, $subgroupId): ?string {
        foreach ($groups as $group) {
            foreach ($group->getSubgroups() as $subgroup) {
                if ($subgroup->getSubgroupId() == $subgroupId) {
                    return $group->getGroupId();
                }
            }
        }
    }
}