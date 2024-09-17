<?php
require 'User.php';

class Owner extends User {
    private $idTBOwner;
    private $directionTBOwner;
    private $photoURLTBOwner;
    private $statusTBOwner;

    function __construct($idTBOwner = 0, $directionTBOwner = "", $photoURLTBOwner = "", $statusTBOwner = true, $id = 0, $nickname = "", $password = "", $active = true, $userType = "", $name = "", $surnames = "", $legalIdentification = "", $phone = "", $email = "") {
        // Llamar al constructor de la clase User
        parent::__construct($id, $nickname, $password, $active, $userType, $name, $surnames, $legalIdentification, $phone, $email);

        $this->idTBOwner = $idTBOwner;
        $this->directionTBOwner = $directionTBOwner;
        $this->photoURLTBOwner = $photoURLTBOwner;
        $this->statusTBOwner = $statusTBOwner;
    }

    public static function fromUser(User $user, $idTBOwner = 0, $directionTBOwner = "", $photoURLTBOwner = "", $statusTBOwner = true) {
        $owner = new self (
            $user->getId(),
            $user->getNickname(),
            $user->getPassword(),
            $user->getActive(),
            $user->getUserType(),
            $user->getName(),
            $user->getSurnames(),
            $user->getLegalIdentification(),
            $user->getPhone(),
            $user->getEmail()
        );

        $owner->idTBOwner = $idTBOwner;
        $owner->directionTBOwner = $directionTBOwner;
        $owner->photoURLTBOwner = $photoURLTBOwner;
        $owner->statusTBOwner = $statusTBOwner;

        return $owner;
    }

    // Getters
    public function getIdTBOwner() {
        return $this->idTBOwner;
    }

    public function getDirectionTBOwner() {
        return $this->directionTBOwner;
    }

    public function getPhotoURLTBOwner() {
        return $this->photoURLTBOwner;
    }
    function getStatusTBOwner () {
        return $this->statusTBOwner;
    }

    public function getFullName(){
        return $this->name." ".$this->surnames;
    }

    // Setters
    public function setIdTBOwner($idTBOwner) {
        $this->idTBOwner = $idTBOwner;
    }

    public function setDirectionTBOwner($photoURLTBOwner) {
        $this->photoURLTBOwner = $photoURLTBOwner;
    }

    public function setPhotoURLTBOwner($directionTBOwner) {
        $this->directionTBOwner = $directionTBOwner;
    }
    function setStatusTBOwner ($statusTBOwner) {
        $this->statusTBOwner = $statusTBOwner;
    }

    public function __toString() {
        return parent::__toString() . // Llama al toString de User
               "ID Propietario: " . $this->idTBOwner . "\n" .
               "Dirección: " . $this->directionTBOwner . "\n" .
               "Foto URL: " . $this->photoURLTBOwner . "\n" .
               "Estado: " . ($this->statusTBOwner ? "Activo" : "Inactivo") . "\n";
    }
}
