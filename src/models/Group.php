<?php

class Group {
    private ?string $groupId;
    private string $fullName;
    private string $shortName;
    private string $accessPassword;
    private array $subgroups = [];

    public function __construct(
        ?string $groupId,
        string $fullName, 
        string $shortName, 
        string $accessPassword
    ) {
        $this->groupId = $groupId;
        $this->fullName = $fullName;
        $this->shortName = $shortName;
        $this->accessPassword = $accessPassword;
    }

    public function getGroupId(): ?string {
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