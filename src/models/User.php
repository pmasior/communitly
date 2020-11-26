<?php

class User {
    private $email;
    private $password;
    private $name;

    public function __construct(string $email, string $password, 
                                string $name) {
        $this->email = $email;
        $this->password = $password;
        $this->name = $name;
    }

    public function getEmail():string {
        return $this->email;
    }

    public function getPassword():string {
        return $this->password;
    }

    public function getName():string {
        return $this->name;
    }

    public function setEmail(string $email) {
        $this->email = $email;
    }

    public function setPassword(string $password) {
        $this->password = $password;
    }

    public function setName(string $name) {
        $this->name = $name;
    }

    // TODO: delete unnecessary methods
}

?>