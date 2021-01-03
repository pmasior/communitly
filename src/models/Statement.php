<?php

class Statement {
    private $header;
    private $content;
    private $attachment;

    public function __construct($header, $content, $attachment) {
        $this->header = $header;
        $this->content = $content;
        $this->attachment = $attachment;
    }

    public function getContent(): string {
        return $this->content;
    }

    public function getHeader(): string {
        return $this->header;
    }

    public function getAttachment(): string {
        return $this->attachment;
    }

    public function setContent(string $content) {
        $this->content = $content;
    }

    public function setHeader(string $header) {
        $this->header = $header;
    }

    public function setAttachment(string $attachment) {
        $this->attachment = $attachment;
    }
}

?>