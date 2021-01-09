<?php

class Thread {
    private $threadId;
    private $subgroupId;
    private $name;

    public function __construct(
        string $threadId,
        string $subgroupId, 
        string $name
    ) {
        $this->threadId = $threadId;
        $this->subgroupId = $subgroupId;
        $this->name = $name;
    }

    public function getSubgroupId() {
        return $this->subgroupId;
    }
    
    public function getThreadId(): string {
        return $this->threadId;
    }

    public function getName(): string {
        return $this->name;
    }
}
?>