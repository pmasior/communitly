<?php

class Attachment {
    private $attachmentId;
    private $filename;
    private $serverFilename;
    private $type;

    public function __construct(
        string $attachmentId,
        string $filename, 
        string $serverFilename, 
        ?string $type
    ) {
        $this->attachmentId = $attachmentId;
        $this->filename = $filename;
        $this->serverFilename = $serverFilename;
        $this->type = $type;
    }

    public function getAttachmentId(): string {
        return $this->attachmentId;
    }
    
    public function getFilename(): string {
        return $this->filename;
    }

    public function getServerFilename(): string {
        return $this->serverFilename;
    }

    public function getType(): ?string {
        return $this->type;
    }
}
?>