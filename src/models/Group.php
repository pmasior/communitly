<?php

class Group {
    private $groupId;
    private $fullName;
    private $shortName;
    private $accessPassword;
    // private $salt;
    private $subgroups = [];

    public function __construct(
        string $groupId,
        string $fullName, 
        string $shortName, 
        string $accessPassword
    ) {
        $this->groupId = $groupId;
        $this->fullName = $fullName;
        $this->shortName = $shortName;
        $this->accessPassword = $accessPassword;
    }

    public function getGroupId() {
        return $this->groupId;
    }
    
    public function getFullName(): string {
        return $this->fullName;
    }

    public function getShortName(): string {
        return $this->shortName;
    }

    public function getAccessPassword(): string {
        return $this->accessPassword;
    }

    public function getSubgroups(): array {
        return $this->subgroups;
    }

    public function addSubgroups(Subgroup $subgroup) {
        $this->subgroups[] = $subgroup;
    }

    public function setSubgroups(array $subgroups) {
        $this->subgroups = $subgroups;
    }
}
?>