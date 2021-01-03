<?php

class User {
    private $email;
    private $password;
    private $firstname;
    private $lastname;

    public function __construct(string $email, string $password, 
                                string $firstname, string $lastname) {
        $this->email = $email;
        $this->password = $password;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
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

    public function setEmail(string $email) {
        $this->email = $email;
    }

    public function setPassword(string $password) {
        $this->password = $password;
    }

    public function setFirstname(string $firstname) {
        $this->firstname = $firstname;
    }

    public function setLastname(string $lastname) {
        $this->lastname = $lastname;
    }

    // TODO: delete unnecessary methods
}

?>