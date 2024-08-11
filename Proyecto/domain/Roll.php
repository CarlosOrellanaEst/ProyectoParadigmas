<?php

class Roll {
    private $idTBRoll;
    private $nameTBRoll;
    private $descriptionTBRoll;
    private $statusTBRoll;
    private $email;

    function __construct ($idTBRoll = 0, $nameTBRoll = "", $descriptionTBRoll="", $statusTBRoll=true) { 
        $this->idTBRoll = $idTBRoll;
        $this->nameTBRoll = $nameTBRoll;
        $this->descriptionTBRoll = $descriptionTBRoll;
        $this->statusTBRoll = $statusTBRoll;
    }

    function getIdTBRoll () { 
        return $this->idTBRoll;
    }
    function getNameTBRoll () {
        return $this->nameTBRoll;
    }
    function getDescriptionTBRoll () {
        return $this->descriptionTBRoll;
    }
    function getStatusTBRoll () {
        return $this->statusTBRoll;
    }

    function setNameTBRoll ($nameTBRoll) { 
        $this->nameTBRoll = $nameTBRoll;
    }
    function setIDTBRoll ($idTBRoll) {
        return $this->idTBRoll = $idTBRoll;
    }
    function setDescriptionTBRoll ($descriptionTBRoll) {
        $this->descriptionTBRoll = $descriptionTBRoll;
    }
    function setStatusTBRoll ($statusTBRoll) {
        $this->statusTBRoll = $statusTBRoll;
    }

}
