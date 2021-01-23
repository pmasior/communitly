<?php

class Thread {
    private ?string $threadId;
    private string $subgroupId;
    private string $name;

    public function __construct(
        ?string $threadId,
        string $subgroupId, 
        string $name
    ) {
        $this->threadId = $threadId;
        $this->subgroupId = $subgroupId;
        $this->name = $name;
    }

    public function getSubgroupId(): string {
        return $this->subgroupId;
    }
    
    public function getThreadId(): string {
        return $this->threadId;
    }

    public function getName(): string {
        return $this->name;
    }
}