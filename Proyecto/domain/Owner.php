<?php
include '../domain/Person.php';

class Owner extends Person{
    private $idTBOwner;
    private $directionTBOwner;
    private $statusTBOwner;

    function __construct($idTBOwner = 0, $directionTBOwner = "", $name = "", $surnames = "", $legalIdentification = "", $phone = "", $email = "", $statusTBOwner = true) {
        parent::__construct($name, $surnames, $legalIdentification, $phone, $email);
        $this->idTBOwner = $idTBOwner;
        $this->directionTBOwner = $directionTBOwner;
        $this->statusTBOwner = $statusTBOwner;
    }

    // Getters
    public function getIdTBOwner() {
        return $this->idTBOwner;
    }

    public function getDirectionTBOwner() {
        return $this->directionTBOwner;
    }

    function getStatusTBOwner () {
        return $this->statusTBOwner;
    }

    // Setters
    public function setIdTBOwner($idTBOwner) {
        $this->idTBOwner = $idTBOwner;
    }

    public function setDirectionTBOwner($directionTBOwner) {
        $this->directionTBOwner = $directionTBOwner;
    }
    function setStatusTBOwner ($statusTBOwner) {
        $this->statusTBOwner = $statusTBOwner;
    }

}