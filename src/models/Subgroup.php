<?php
require_once 'Thread.php';

class Subgroup {
    private ?string $subgroupId;
    private string $fullName;
    private string $shortName;
    private array $threads = [];

    public function __construct(
        ?string $subgroupId,
        string $fullName, 
        string $shortName
    ) {
        $this->subgroupId = $subgroupId;
        $this->fullName = $fullName;
        $this->shortName = $shortName;
    }

    public function getSubgroupId(): ?string {
        return $this->subgroupId;
    }
    
    public function getFullName(): string {
        return $this->fullName;
    }

    public function getShortName(): string {
        return $this->shortName;
    }

    public function getThreads(): array {
        return $this->threads;
    }

    public function addThreads(Thread $thread) {
        $this->threads[] = $thread;
    }

    public function setThreads(array $threads) {
        $this->threads = $threads;
    }
}