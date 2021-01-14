<?php

require_once 'Repository.php';
require_once __DIR__ . '/../models/Group.php';
require_once __DIR__ . '/../models/Subgroup.php';
require_once __DIR__ . '/../models/Thread.php';

class GroupRepository extends Repository {
    const SELECT_GROUPS_FOR_USER = 'SELECT * FROM select_groups_for_user(?);';
    const SELECT_SUBGROUPS_FOR_GROUP = 'SELECT * FROM select_subgroups_for_group(?);';
    const SELECT_SUBGROUPS_FOR_USER_AND_GROUP = 'SELECT * FROM select_subgroups_for_user_and_group(?, ?);';
    const SELECT_SUBGROUP_FOR_USER_AND_SUBGROUP_ID = 'SELECT * FROM select_subgroup_for_user_and_subgroup_id(?, ?);';
    const SELECT_THREADS_FOR_SUBGROUP = 'SELECT * FROM select_threads_for_subgroup(?);';
    const SELECT_THREADS_FOR_USER_AND_SUBGROUP = 'SELECT * FROM select_threads_for_user_and_subgroup(?, ?);';
    const OPT_IN_USER_TO_GROUP = 'SELECT * FROM opt_in_user_to_group(?, ?);';
    const OPT_OUT_USER_FROM_GROUP = 'SELECT * FROM opt_out_user_from_group(?, ?);';
    const OPT_IN_USER_TO_SUBGROUP = 'SELECT * FROM opt_in_user_to_subgroup(?, ?);';
    const OPT_OUT_USER_FROM_SUBGROUP = 'SELECT * FROM opt_out_user_from_subgroup(?, ?);';
    const OPT_IN_USER_TO_THREAD = 'SELECT * FROM opt_in_user_to_thread(?, ?);';
    const OPT_OUT_USER_FROM_THREAD = 'SELECT * FROM opt_out_user_from_thread(?, ?);';
    const INSERT_GROUP = 'SELECT insert_group(?, ?, ?);';
    const INSERT_SUBGROUP = 'SELECT insert_subgroup(?, ?, ?);';
    const INSERT_THREAD = 'SELECT insert_thread(?, ?);';

    public function getGroups($userId, bool $includeSubgroups, bool $includeThreads, bool $showAvailableToJoin): array {
        $groups = $this->getGroupsForUser($userId);
        if ($includeSubgroups) {
            $this->addSubgroupsToGroups($groups, $userId, $includeThreads, $showAvailableToJoin);
        }
        return $groups;
    }

    public function getSubgroup($userId, $subgroupId) {
        $subgroupQueryResult = $this->select(
            self::SELECT_SUBGROUP_FOR_USER_AND_SUBGROUP_ID,
            [$userId, $subgroupId]
        );
        $subgroupsArray = $this->convertDatabaseResultToObjects($subgroupQueryResult, 'Subgroup');
        return $subgroupsArray[0];
    }

    public function getThreads($subgroupId, $userId = NULL): array {
        if ($userId) {
            $queryResult = $this->select(
                self::SELECT_THREADS_FOR_USER_AND_SUBGROUP,
                [$userId, $subgroupId]
            );
        } else {
            $queryResult = $this->select(
                self::SELECT_THREADS_FOR_SUBGROUP,
                [$subgroupId]
            );
        }
        return $this->convertDatabaseResultToObjects($queryResult, 'Thread');
    }

    public function createGroup(Group $group) {
        $queryResult = $this->insert(
            self::INSERT_GROUP,
            [
                $group->getFullName(),
                $group->getShortName(),
                $group->getAccessPassword()
            ]
        );
        return $queryResult['insert_group'];
    }

    public function createSubgroup(Subgroup $subgroup, $groupId) {
        $queryResult = $this->insert(
            self::INSERT_SUBGROUP,
            [
                $groupId,
                $subgroup->getFullName(),
                $subgroup->getShortName()
            ]
        );
        return $queryResult['insert_subgroup'];
    }

    public function createThread(Thread $thread) {
        $queryResult = $this->insert(
            self::INSERT_THREAD,
            [
                $thread->getSubgroupId(),
                $thread->getName()
            ]
        );
        return $queryResult['insert_subgroup'];
    }

    public function optInUserToGroup($userId, $groupAccessPassword) {
//        TODO: zmienić NULL w funkcji SQL
        $queryResult = $this->insert(
            self::OPT_IN_USER_TO_GROUP,
            [$userId, $groupAccessPassword]
        );
        return $queryResult['opt_in_user_to_group'];
    }

    public function optOutUserFromGroup($userId, $groupId) {
        $queryResult = $this->insert(
            self::OPT_OUT_USER_FROM_GROUP,
            [$userId, $groupId]
        );
        return $queryResult['opt_out_user_from_group'];
    }

    public function optInUserToSubgroup($userId, $subgroupId) {
        $queryResult = $this->insert(
            self::OPT_IN_USER_TO_SUBGROUP,
            [$userId, $subgroupId]
        );
        return $queryResult['opt_in_user_to_subgroup'];
    }

    public function optOutUserFromSubgroup($userId, $subgroupId) {
        $queryResult = $this->insert(
            self::OPT_OUT_USER_FROM_SUBGROUP,
            [$userId, $subgroupId]
        );
        return $queryResult['opt_out_user_from_subgroup'];
    }

    public function optInUserToThread($userId, $threadId) {
        $queryResult = $this->insert(
            self::OPT_IN_USER_TO_THREAD,
            [$userId, $threadId]
        );
        return $queryResult['opt_in_user_to_thread'];
    }

    public function optOutUserFromThread($userId, $threadId) {
        $queryResult = $this->insert(
            self::OPT_OUT_USER_FROM_THREAD,
            [$userId, $threadId]
        );
        return $queryResult['opt_out_user_from_thread'];
    }

    private function getGroupsForUser($userId): array {
        $queryResult = $this->select(
            self::SELECT_GROUPS_FOR_USER,
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
            $queryResult = $this->select(
                self::SELECT_SUBGROUPS_FOR_USER_AND_GROUP,
                [$userId, $groupId]
            );
        } else {
            $queryResult = $this->select(
                self::SELECT_SUBGROUPS_FOR_GROUP,
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
?>