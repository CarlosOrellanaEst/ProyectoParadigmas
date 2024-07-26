<?php

class Roll {
    private $idTBRoll;
    private $nameTBRoll;
    private $descriptionTBRoll;

    function __construct ($idTBRoll = 0, $nameTBRoll = "", $descriptionTBRoll="") { 
        $this->idTBRoll = $idTBRoll;
        $this->nameTBRoll = $nameTBRoll;
        $this->descriptionTBRoll = $descriptionTBRoll;
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

    function setNameTBRoll ($nameTBRoll) { 
        $this->nameTBRoll = $nameTBRoll;
    }
    function setIDTBRoll ($idTBRoll) {
        return $this->idTBRoll = $idTBRoll;
    }
    function setDescriptionTBRoll ($descriptionTBRoll) {
        $this->descriptionTBRoll = $descriptionTBRoll;
    }
}