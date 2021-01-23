<?php

class Attachment {
    private string $attachmentId;
    private string $filename;
    private ?string $type;

    public function __construct(
        string $attachmentId,
        string $filename,
        ?string $type
    ) {
        $this->attachmentId = $attachmentId;
        $this->filename = $filename;
        $this->type = $type;
    }

    public function getAttachmentId(): string {
        return $this->attachmentId;
    }
    
    public function getFilename(): string {
        return $this->filename;
    }

    public function getType(): ?string {
        return $this->type;
    }
}