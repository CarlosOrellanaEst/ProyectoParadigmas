<?php
class User extends Person{
    private $id;
    private $userName;
    private $password;
    private $active;
    private $userType;

    

    // Getters
    public function getId() {
        return $this->id;
    }

    public function getUserName() {
        return $this->userName;
    }

    public function getUserLastName() {
        return $this->userLastName;
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
    public function setUserName($userName) {
        $this->userName = $userName;
    }

    public function setUserLastName($userLastName) {
        $this->userLastName = $userLastName;
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
