<?php

class User {
    private string $email;
    private ?string $password;
    private ?string $firstname;
    private ?string $lastname;
    private ?int $userId;

    public function __construct(
        ?int $userId,
        string $email,
        ?string $password = NULL,
        ?string $firstname = NULL,
        ?string $lastname = NULL
    ) {
        $this->userId = $userId;
        $this->email = $email;
        $this->password = $password;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
    }

    public function getUserId(): ?int {
        return $this->userId;
    }

    public function getEmail():string {
        return $this->email;
    }

    public function getPassword():string {
        return $this->password;
    }

    public function getFirstname():string {
        return $this->firstname;
    }

    public function getLastname():string {
        return $this->lastname;
    }
}