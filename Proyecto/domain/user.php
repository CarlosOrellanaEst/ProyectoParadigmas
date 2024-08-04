<?php
class User {
    private $id;
    private $userName;
    private $userLastName;
    private $password;
    private $phone;
    private $userID;
    private $active;
    private $userType;

    public function __construct($id, $userName, $userLastName, $password, $phone, $userID, $active, $userType) {
        $this->id = $id;
        $this->userName = $userName;
        $this->userLastName = $userLastName;
        $this->password = $password;
        $this->phone = $phone;
        $this->userID = $userID;
        $this->active = $active;
        $this->userType = $userType;
    }

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

    public function getUserID() {
        return $this->userID;
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
        $this->userID = $userID;
    }

    public function setActive($active) {
        $this->active = $active;
    }

    public function setUserType($userType) {
        $this->userType = $userType;
    }
}
?>
