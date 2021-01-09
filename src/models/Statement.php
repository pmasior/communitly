<?php

class Statement {
    private $statementId;
    private $title;
    private $content;
    private $creationDate;
    private $creationUser;
    private $approveDate;
    private $approveUser;
    private $attachments = [];
    private $sourceURL;

    public function __construct(
        ?string $statementId,
        string $title, 
        string $content, 
        ?DateTime $creationDate, 
        string $creationUser,
        // ?DateTime $approveDate, 
        // ?string $approveUser,
        // $attachments, 
        ?string $sourceURL
    ) {
        $this->statementId = $statementId;
        $this->title = $title;
        $this->content = $content;
        $this->creationDate = $creationDate;
        $this->creationUser = $creationUser;
        // $this->approveDate = $approveDate;
        // $this->approveUser = $approveUser;
        // $this->attachments = $attachments;
        $this->sourceURL = $sourceURL;
    }

    public function getStatementId(): string {
        return $this->statementId;
    }

    public function getContent(): string {
        return $this->content;
    }

    public function getHeader(): string {
        return $this->title;
    }

    public function getAttachments(): array {
        return $this->attachments;
    }

    public function getCreationDate(): DateTime {
        return $this->creationDate;
    }

    public function getApproveDate(): ?DateTime {
        return $this->approveDate;
    }

    public function getSourceURL(): ?string {
        return $this->sourceURL;
    }

    public function setContent(string $content) {
        $this->content = $content;
    }

    public function setHeader(string $title) {
        $this->title = $title;
    }

    // public function setAttachment(string $attachment) {
    //     $this->attachment = $attachment;
    // }

    public function addAttachment(Attachment $attachment) {
        $this->attachments[] = $attachment;
    }
}

?>