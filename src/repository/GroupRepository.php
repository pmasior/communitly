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
        $queryResult = $this->select(
            self::SELECT_GROUPS_FOR_USER,
            [$userId]
        );
        if ($queryResult) {
            $groups = $this->convertDatabaseResultToObjects($queryResult, 'Group');
            $this->getSubgroupsForGroups($userId, $groups);
            return $groups;
        }
        return NULL; // TODO: check return [];
    }

    public function getSubgroup($userId, $subgroupId) {
        $subgroupQueryResult = $this->select(
            self::SELECT_SUBGROUP_FOR_USER_AND_SUBGROUP_ID,
            [$userId, $subgroupId]
        );
        if ($subgroupQueryResult) {
            $subgroupsArray = $this->convertDatabaseResultToObjects($subgroupQueryResult, 'Subgroup');
            return $subgroupsArray[0];
        }
        return NULL; 
    }

    private function getSubgroupsForGroups($userId, $groups) {
        foreach ($groups as $group) {
            $subgroupsQueryResult = $this->select(
                self::SELECT_SUBGROUPS_FOR_USER_AND_GROUP,
                [$userId, $group->getGroupId()]
            );
            if ($subgroupsQueryResult) {
                $subgroups = $this->convertDatabaseResultToObjects($subgroupsQueryResult, 'Subgroup');
                foreach ($subgroups as $subgroup) {
                    $group->addSubgroups($subgroup);
                }
            }
        }
    }

    public function getThreadsForSubgroup($subgroupId) {
        $threadsQueryResult = $this->select(
            self::SELECT_THREADS_FOR_SUBGROUP,
            [$subgroupId]
        );
        if ($threadsQueryResult) {
            $threadsArray = $this->convertDatabaseResultToObjects($threadsQueryResult, 'Thread');
            return $threadsArray;
        }
        return NULL;
    }
    
    private function convertDatabaseResultToObjects(array $records, string $convertTo): ?array {
        if (!$records) {
            return NULL;
        }
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