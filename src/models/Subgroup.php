<?php

class Subgroup {
    private $subgroupId;
    private $fullName;
    private $shortName;

    public function __construct(
        string $subgroupId,
        string $fullName, 
        string $shortName
    ) {
        $this->subgroupId = $subgroupId;
        $this->fullName = $fullName;
        $this->shortName = $shortName;
    }

    public function getSubgroupId() {
        return $this->subgroupId;
    }
    
    public function getFullName(): string {
        return $this->fullName;
    }

    public function getShortName(): string {
        return $this->shortName;
    }
}
?>