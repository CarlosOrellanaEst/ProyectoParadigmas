<?php
include_once  '../domain/Person.php';

class Owner extends Person{
     private $idTBOwner;
     private $directionTBOwner;
     private $photoURLTBOwner;
     private $statusTBOwner;

     function __construct($idTBOWner = 0, $directionTBOwner="", $name="", $surnames="", $legalIdentification="", $phone="", $email="", $photoURLTBOwner="", $statusTBOwner=true){

        parent::__construct($name, $surnames, $legalIdentification, $phone, $email);

        $this->idTBOwner=$idTBOWner;
        $this->directionTBOwner=$directionTBOwner;
        $this->photoURLTBOwner=$photoURLTBOwner;
        $this->statusTBOwner=$statusTBOwner;
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

}
