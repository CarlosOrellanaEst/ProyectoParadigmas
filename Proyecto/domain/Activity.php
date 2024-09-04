<?php

class Activity {
    private $idTBActivity;
    private $nameTBActivity;
    private $attributeTBActivityArray;
    private $dataAttributeTBActivityArray;
    private $statusTBActivity;

    function __construct ($idTBActivity = 0, $nameTBActivity = "", $attributeTBActivityArray="",$dataAttributeTBActivityArray="",$statusTBActivity=true) { 
        $this->idTBActivity = $idTBActivity;
        $this->nameTBActivity = $nameTBActivity;
        $this->attributeTBActivityArray = $attributeTBActivityArray;
        $this->dataAttributeTBActivityArray = $dataAttributeTBActivityArray;
        $this->statusTBActivity = $statusTBActivity;
    }

    // Getter
    
    public function getIdTBActivity() {
        return $this->idTBActivity;
    }

    public function getNameTBActivity() {
        return $this->nameTBActivity;
    }

    public function getAttributeTBActivityArray() {
        return $this->attributeTBActivityArray;
    }

    public function getDataAttributeTBActivityArray() {
        return $this->dataAttributeTBActivityArray;
    }

    public function getStatusTBActivity() {
        return $this->statusTBActivity;
    }

    // Setter

    public function setIdTBActivity($idTBActivity) {
        $this->idTBActivity = $idTBActivity;
    }

    public function setNameTBActivity($nameTBActivity) {
        $this->nameTBActivity = $nameTBActivity;
    }

    public function setAttributeTBActivityArray($attributeTBActivityArray) {
        $this->attributeTBActivityArray = $attributeTBActivityArray;
    }


    public function setDataAttributeTBActivityArray($dataAttributeTBActivityArray) {
        $this->dataAttributeTBActivityArray = $dataAttributeTBActivityArray;
    }

    public function setStatusTBActivity($statusTBActivity) {
        $this->statusTBActivity = $statusTBActivity;
    }




}