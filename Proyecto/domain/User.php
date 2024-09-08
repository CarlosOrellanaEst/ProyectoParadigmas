<?php

require 'Person.php';
class User extends Person{
    private $id;
    private $nickname;
    private $password;
    private $active;
    private $userType;

    function __construct($id = 0, $nickname = "", $password = "", $active = false, $userType = "", $name = "", $surnames = "", $legalIdentification = "", $phone = "", $email = "") {
        // Llamar al constructor de la clase Person
        parent::__construct($name, $surnames, $legalIdentification, $phone, $email);

        $this->id = $id;
        $this->nickname = $nickname;
        $this->password = $password;
        $this->active = $active;
        $this->userType = $userType;
    }

    // Getters
    public function getId() {
        return $this->id;
    }
    public function getNickname() {
        return $this->nickname;
    }
    public function getPassword() {
        return $this->password;
    }
    public function getPhone() {
        return $this->phone;
    }
    public function getActive() {
        return $this->active;
    }
    public function getUserType() {
        return $this->userType;
    }

    // Setters
    public function setNickname($nickname) {
        $this->nickname = $nickname;
    }
    public function setPassword($password) {
        $this->password = $password;
    }
    public function setPhone($phone) {
        $this->phone = $phone;
    }
    public function setUserID($userID) {
        $this->id = $userID;
    }
    public function setActive($active) {
        $this->active = $active;
    }
    public function setUserType($userType) {
        $this->userType = $userType;
    }
}
