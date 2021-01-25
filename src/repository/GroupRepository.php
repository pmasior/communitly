<?php

require_once 'Repository.php';
require_once __DIR__ . '/../models/Group.php';
require_once __DIR__ . '/../models/Subgroup.php';
require_once __DIR__ . '/../models/Thread.php';

class GroupRepository extends Repository {
    protected static ?Repository $uniqueInstance;

    public function __construct() {
        self::$uniqueInstance = Repository::getInstance();
    }

    public function getGroups($userId, bool $includeSubgroups, bool $includeThreads, bool $showAvailableToJoin): array {
        $groups = $this->getGroupsForUser($userId);
        if ($includeSubgroups) {
            $this->addSubgroupsToGroups($groups, $userId, $includeThreads, $showAvailableToJoin);
        }
        return $groups;
    }

    public function getSubgroup($userId, $subgroupId) {
        $subgroupQueryResult = self::$uniqueInstance->executeAndFetchAll(
            'SELECT * FROM select_subgroup_for_user_and_subgroup_id(?, ?);',
            [$userId, $subgroupId]
        );
        $subgroupsArray = $this->convertDatabaseResultToObjects($subgroupQueryResult, 'Subgroup');
        return $subgroupsArray[0];
    }

    public function getThreads($subgroupId, $userId = NULL): array {
        if ($userId) {
            $queryResult = self::$uniqueInstance->executeAndFetchAll(
                'SELECT * FROM select_threads_for_user_and_subgroup(?, ?);',
                [$userId, $subgroupId]
            );
        } else {
            $queryResult = self::$uniqueInstance->executeAndFetchAll(
                'SELECT * FROM select_threads_for_subgroup(?);',
                [$subgroupId]
            );
        }
        return $this->convertDatabaseResultToObjects($queryResult, 'Thread');
    }

    public function createGroup(Group $group) {
        $queryResult = self::$uniqueInstance->executeAndFetch(
            'SELECT insert_group(?, ?, ?);',
            [
                $group->getFullName(),
                $group->getShortName(),
                $group->getAccessPassword()
            ]
        );
        return $queryResult['insert_group'];
    }

    public function createSubgroup(Subgroup $subgroup, $groupId) {
        $queryResult = self::$uniqueInstance->executeAndFetch(
            'SELECT insert_subgroup(?, ?, ?);',
            [
                $groupId,
                $subgroup->getFullName(),
                $subgroup->getShortName()
            ]
        );
        return $queryResult['insert_subgroup'];
    }

    public function createThread(Thread $thread) {
        $queryResult = self::$uniqueInstance->executeAndFetch(
            'SELECT insert_thread(?, ?);',
            [
                $thread->getSubgroupId(),
                $thread->getName()
            ]
        );
        return $queryResult['insert_subgroup'];
    }

    public function deleteGroup($groupId) {
        self::$uniqueInstance->executeAndFetch(
            'SELECT * FROM delete_group(:_id_groups);',
            [$groupId]
        );
    }

    public function deleteSubgroup($subgroupId) {
        self::$uniqueInstance->executeAndFetch(
            'SELECT * FROM delete_subgroup(:_id_subgroups);',
            [$subgroupId]
        );
    }

    public function deleteThread($threadId) {
        $queryResult = self::$uniqueInstance->executeAndFetch(
            'SELECT * FROM delete_thread(:_id_threads);',
            [$threadId]
        );
        return $queryResult['insert_subgroup'];
    }

    public function setAdminForGroup($groupId, $userId) {
        self::$uniqueInstance->executeAndFetch(
            'SELECT insert_admin_into_users_types_in_groups(:_id_users, :_id_groups);',
            [$userId, $groupId]
        );
    }

    public function optInUserToGroup($userId, $groupAccessPassword) {
        $queryResult = self::$uniqueInstance->executeAndFetch(
            'SELECT * FROM opt_in_user_to_group(?, ?);',
            [$userId, $groupAccessPassword]
        );
        return $queryResult['opt_in_user_to_group'];
    }

    public function optOutUserFromGroup($userId, $groupId) {
        $queryResult = self::$uniqueInstance->executeAndFetch(
            'SELECT * FROM opt_out_user_from_group(?, ?);',
            [$userId, $groupId]
        );
        return $queryResult['opt_out_user_from_group'];
    }

    public function optInUserToSubgroup($userId, $subgroupId) {
        $queryResult = self::$uniqueInstance->executeAndFetch(
            'SELECT * FROM opt_in_user_to_subgroup(?, ?);',
            [$userId, $subgroupId]
        );
        return $queryResult['opt_in_user_to_subgroup'];
    }

    public function optOutUserFromSubgroup($userId, $subgroupId) {
        $queryResult = self::$uniqueInstance->executeAndFetch(
            'SELECT * FROM opt_out_user_from_subgroup(?, ?);',
            [$userId, $subgroupId]
        );
        return $queryResult['opt_out_user_from_subgroup'];
    }

    public function optInUserToThread($userId, $threadId) {
        $queryResult = self::$uniqueInstance->executeAndFetch(
            'SELECT * FROM opt_in_user_to_thread(?, ?);',
            [$userId, $threadId]
        );
        return $queryResult['opt_in_user_to_thread'];
    }

    public function optOutUserFromThread($userId, $threadId) {
        $queryResult = self::$uniqueInstance->executeAndFetch(
            'SELECT * FROM opt_out_user_from_thread(?, ?);',
            [$userId, $threadId]
        );
        return $queryResult['opt_out_user_from_thread'];
    }

    public function beginTransaction() {
        self::$uniqueInstance->beginTransaction();
    }

    public function commit() {
        self::$uniqueInstance->commit();
    }

    private function getGroupsForUser($userId): array {
        $queryResult = self::$uniqueInstance->executeAndFetchAll(
            'SELECT * FROM select_groups_for_user(?);',
            [$userId]
        );
        return $this->convertDatabaseResultToObjects($queryResult, 'Group');
    }

    private function addSubgroupsToGroups($groups, $userId, bool $includeThreads, bool $showAvailableToJoin) {
        foreach ($groups as $group) {
            if ($showAvailableToJoin) {
                $subgroups = $this->getSubgroups($group->getGroupId());
            } else {
                $subgroups = $this->getSubgroups($group->getGroupId(), $userId);
            }
            $group->setSubgroups($subgroups);
            if ($includeThreads) {
                $this->addThreadsToSubgroups($subgroups, $userId, $showAvailableToJoin);
            }
        }
    }

    private function getSubgroups($groupId, $userId=NULL): array {
        if ($userId) {
            $queryResult = self::$uniqueInstance->executeAndFetchAll(
                'SELECT * FROM select_subgroups_for_user_and_group(?, ?);',
                [$userId, $groupId]
            );
        } else {
            $queryResult = self::$uniqueInstance->executeAndFetchAll(
                'SELECT * FROM select_subgroups_for_group(?);',
                [$groupId]
            );
        }
        return $this->convertDatabaseResultToObjects($queryResult, 'Subgroup');
    }

    private function addThreadsToSubgroups($subgroups, $userId, bool $showAvailableToJoin) {
        foreach ($subgroups as $subgroup) {
            if ($showAvailableToJoin) {
                $threads = $this->getThreads($subgroup->getSubgroupId());
            } else {
                $threads = $this->getThreads($subgroup->getSubgroupId(), $userId);
            }
            $subgroup->setThreads($threads);
        }
    }

    private function convertDatabaseResultToObjects(array $records, string $convertTo): ?array {
        $convert = 'convertTo' . $convertTo;
        $result = [];
        foreach ($records as $record) {
            $result[] = $this->$convert($record);
        }
        return $result;
    }

    private function convertToGroup(array $group): Group {
        return new Group(
            $group['id_groups'],
            $group['full_name'],
            $group['short_name'],
            $group['access_password']
        );
    }

    private function convertToSubgroup(array $subgroup): Subgroup {
        return new Subgroup(
            $subgroup['id_subgroups'],
            $subgroup['full_name'],
            $subgroup['short_name']
        );
    }
    
    private function convertToThread(array $thread): Thread {
        return new Thread(
            $thread['id_threads'],
            $thread['id_subgroups'], 
            $thread['name']
        );
    }
}