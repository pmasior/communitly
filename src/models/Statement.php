<?php
require_once 'User.php';

class Statement {
    private ?string $statementId;
    private string $title;
    private string $content;
    private ?DateTime $creationDate;
    private User $creationUser;
    private ?DateTime $approveDate;
    private ?User $approveUser;
    private array $attachments = [];
    private ?string $sourceURL;

    public function __construct(
        ?string $statementId,
        string $title, 
        string $content, 
        ?DateTime $creationDate, 
        User $creationUser,
        ?DateTime $approveDate,
        ?User $approveUser,
        ?string $sourceURL
    ) {
        $this->statementId = $statementId;
        $this->title = $title;
        $this->content = $content;
        $this->creationDate = $creationDate;
        $this->creationUser = $creationUser;
        $this->approveDate = $approveDate;
        $this->approveUser = $approveUser;
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

    public function getCreationUser(): User {
        return $this->creationUser;
    }

    public function getApproveUser(): ?User {
        return $this->approveUser;
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

    public function addAttachment(Attachment $attachment) {
        $this->attachments[] = $attachment;
    }
}