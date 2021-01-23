<?php

class Link {
    private ?string $linkId;
    private string $title;
    private string $url;
    private ?string $note;

    public function __construct(
        ?string $linkId,
        string $title,
        string $url,
        ?string $note
    ) {
        $this->linkId = $linkId;
        $this->title = $title;
        $this->url = $url;
        $this->note = $note;
    }

    public function getLinkId(): ?string {
        return $this->linkId;
    }

    public function getTitle(): string {
        return $this->title;
    }

    public function getUrl(): string {
        return $this->url;
    }

    public function getNote(): string {
        return $this->note;
    }
}