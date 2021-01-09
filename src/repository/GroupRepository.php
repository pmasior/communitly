<?php

require_once 'Repository.php';
require_once __DIR__ . '/../models/Group.php';
require_once __DIR__ . '/../models/Subgroup.php';
require_once __DIR__ . '/../models/Thread.php';

class GroupRepository extends Repository {
    const SELECT_GROUPS_FOR_USER = 'SELECT * FROM select_groups_for_user(?);';
    const SELECT_SUBGROUPS_FOR_USER_AND_GROUP = 'SELECT * FROM select_subgroups_for_user_and_group(?, ?);';
    const SELECT_SUBGROUP_FOR_USER_AND_SUBGROUP_ID = 'SELECT * FROM select_subgroup_for_user_and_subgroup_id(?, ?);';
    const SELECT_THREADS_FOR_SUBGROUP = 'SELECT * FROM select_threads_for_subgroup(?);';

    
    public function getGroups($userId) {
        $groupsQueryResult = $this->select(self::SELECT_GROUPS_FOR_USER, [$userId]);
        $groups = $this->convertDatabaseResultToGroups($groupsQueryResult);
        $this->getSubgroupsForGroups($userId, $groups);
        return $groups;
    }

    public function getSubgroup($userId, $subgroupId) {
        $subgroupQueryResult = $this->select(self::SELECT_SUBGROUP_FOR_USER_AND_SUBGROUP_ID, [$userId, $subgroupId]);
        if ($subgroupQueryResult) {
            $subgroupsArray = $this->convertDatabaseResultToSubgroups($subgroupQueryResult);
            return $subgroupsArray[0];
        }
        return NULL; 
    }

    public function getThreadsForSubgroup($subgroupId) {
        $threadsQueryResult = $this->select(self::SELECT_THREADS_FOR_SUBGROUP, [$subgroupId]);
        if ($threadsQueryResult) {
            $threadsArray = $this->convertDatabaseResultToThreads($threadsQueryResult);
            return $threadsArray;
        }
        return NULL; 

    }

    private function getSubgroupsForGroups($userId, $groups) {
        foreach ($groups as $group) {
            $subgroupsQueryResult = $this->select(self::SELECT_SUBGROUPS_FOR_USER_AND_GROUP, [$userId, $group->getGroupId()]);
            $subgroups = $this->convertDatabaseResultToSubgroups($subgroupsQueryResult);
            foreach ($subgroups as $subgroup) {
                $group->addSubgroups($subgroup);
            }
        }
    }
    
    private function convertDatabaseResultToGroups(array $groups): ?array {
        if (!$groups) {
            return NULL;
        }
        $result = [];
        foreach ($groups as $group) {
            $result[] = $this->convertDatabaseResultToGroup($group);
        }
        return $result;
    }
    
    private function convertDatabaseResultToGroup(array $group): Group {
        return new Group(
            $group['id_groups'],
            $group['full_name'], 
            $group['short_name'], 
            $group['access_password']
        );
    }

    private function convertDatabaseResultToSubgroups(array $subgroups): ?array {
        if (!$subgroups) {
            return NULL;
        }
        $result = [];
        foreach ($subgroups as $subgroup) {
            $result[] = $this->convertDatabaseResultToSubgroup($subgroup);
        }
        return $result;
    }
    
    private function convertDatabaseResultToSubgroup(array $subgroup): Subgroup {
        return new Subgroup(
            $subgroup['id_subgroups'],
            $subgroup['full_name'], 
            $subgroup['short_name']
        );
    }

    private function convertDatabaseResultToThreads(array $threads): ?array {
        if (!$threads) {
            return NULL;
        }
        $result = [];
        foreach ($threads as $thread) {
            $result[] = $this->convertDatabaseResultToThread($thread);
        }
        return $result;
    }
    
    private function convertDatabaseResultToThread(array $thread): Thread {
        return new Thread(
            $thread['id_threads'],
            $thread['id_subgroups'], 
            $thread['name']
        );
    }
}
?>