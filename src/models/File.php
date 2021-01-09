<?php

class File {
    private $name;
    private $type;
    private $tmpName;
    private $error;
    private $size;

    public function __construct(
        string $name,
        string $type, 
        string $tmpName, 
        ?string $error,  //TODO: usunąć error
        string $size
    ) {
        $this->name = $name;
        $this->type = $type;
        $this->tmpName = $tmpName;
        $this->error = $error;
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

    public function getError(): string {
        return $this->error;
    }

    public function getSize(): string {
        return $this->size;
    }
}
?>