<?php

class Person {
   
    private $name;
    private $surnames;
    private $legalIdentification;
    private $phone;
    private $email;

    function __construct($name="", $surnames="", $legalIdentification="", $phone="", $email=""){

        $this->name = $name;
        $this->surnames = $surnames;
        $this->legalIdentification = $legalIdentification;
        $this->phone = $phone;
        $this->email = $email;
    }
 // Getters
 public function getName() {
    return $this->name;
}

public function getSurnames() {
    return $this->surnames;
}

public function getLegalIdentification() {
    return $this->legalIdentification;
}

public function getPhone() {
    return $this->phone;
}

public function getEmail() {
    return $this->email;
}

// Setters
public function setName($name) {
    $this->name = $name;
}

public function setSurnames($surnames) {
    $this->surnames = $surnames;
}

public function setLegalIdentification($legalIdentification) {
    $this->legalIdentification = $legalIdentification;
}

public function setPhone($phone) {
    $this->phone = $phone;
}

public function setEmail($email) {
    $this->email = $email;
}

}