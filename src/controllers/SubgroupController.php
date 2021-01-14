<?php
require_once 'AppController.php';
require_once __DIR__ . '/../repository/GroupRepository.php';
require_once __DIR__ . '/../repository/StatementRepository.php';
require_once __DIR__ . '/../repository/UserRepository.php';

class SubgroupController extends AppController {
    private $groupRepository;
    private $statementRepository;

    public function __construct() {
        parent::__construct();
        $this->groupRepository = new GroupRepository();
        $this->statementRepository = new StatementRepository();
    }

    public function subgroup($subgroupId) {
        $groupsInMenu = $this->groupRepository->getGroups($_SESSION['userId'], true, false, false);
        $displayedSubgroup = $this->groupRepository->getSubgroup($_SESSION['userId'], $subgroupId);
        $statements = $this->statementRepository->getStatements($_SESSION['userId'], $subgroupId);
        $allThreadsInSubgroup = $this->groupRepository->getThreads($subgroupId);

        $this->render('subgroup', [
            'groups' => $groupsInMenu, 
            'statements' => $statements, 
            'subgroup' => $displayedSubgroup,
            'id' => $subgroupId,
            'allThreadsInSubgroup' => $allThreadsInSubgroup
        ]);
    }

    public function dashboard() {
        $userFirstname = $_SESSION['userFirstName'];
        $groupsInMenu = $this->groupRepository->getGroups($_SESSION['userId'], true, false, false);
        
        $this->render('dashboard', [
            'userFirstname' => $userFirstname,
            'groups' => $groupsInMenu
        ]);
    }
}
?>