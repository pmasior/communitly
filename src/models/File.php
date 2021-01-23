<?php

class File {
    private string $name;
    private string $type;
    private string $tmpName;
    private string $size;

    public function __construct(
        string $name,
        string $type, 
        string $tmpName,
        string $size
    ) {
        $this->name = $name;
        $this->type = $type;
        $this->tmpName = $tmpName;
        $this->size = $size;
    }

    public function getName(): string {
        return $this->name;
    }
    
    public function getType(): ?string {
        return $this->type;
    }

    public function getTmpName(): string {
        return $this->tmpName;
    }

    public function getSize(): string {
        return $this->size;
    }
}