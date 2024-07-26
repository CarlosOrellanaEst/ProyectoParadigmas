<?php

class Bull {

    private $idTBBull;
    private $codeTBBull;
    private $ranchTBBull;
    private $nameTBBull;
    private $commercialCaseTBBull;
    private $buyDateTBBull;
    private $strawsQuantityTBBull;
    private $strawsPriceTBBull;

    function Bull($idTBBull, $codeTBBull,$ranchTBBull, $nameTBBull, $commercialCaseTBBull, $buyDateTBBull, $strawsQuantityTBBull, $strawsPriceTBBull) {
        $this->idTBBull = $idTBBull;
        $this->codeTBBull = $codeTBBull;
        $this->ranchTBBull = $ranchTBBull;
        $this->nameTBBull = $nameTBBull;
        $this->commercialCaseTBBull = $commercialCaseTBBull;
        $this->buyDateTBBull = $buyDateTBBull;
        $this->strawsQuantityTBBull = $strawsQuantityTBBull;
        $this->strawsPriceTBBull = $strawsPriceTBBull;
    }

    function getIdTBBull() {
        return $this->idTBBull;
    }
    
    public function getRanchTBBull() {
        return $this->ranchTBBull;
    }

    public function setRanchTBBull($ranchTBBull) {
        $this->ranchTBBull = $ranchTBBull;
    }

    function getCodeTBBull() {
        return $this->codeTBBull;
    }

    function getNameTBBull() {
        return $this->nameTBBull;
    }

    function getCommercialCaseTBBull() {
        return $this->commercialCaseTBBull;
    }

    function getBuyDateTBBull() {
        return $this->buyDateTBBull;
    }

    function getStrawsQuantityTBBull() {
        return $this->strawsQuantityTBBull;
    }

    function getStrawsPriceTBBull() {
        return $this->strawsPriceTBBull;
    }

    function setIdTBBull($idTBBull) {
        $this->idTBBull = $idTBBull;
    }

    function setCodeTBBull($codeTBBull) {
        $this->codeTBBull = $codeTBBull;
    }

    function setNameTBBull($nameTBBull) {
        $this->nameTBBull = $nameTBBull;
    }

    function setCommercialCaseTBBull($commercialCaseTBBull) {
        $this->commercialCaseTBBull = $commercialCaseTBBull;
    }

    function setBuyDateTBBull($buyDateTBBull) {
        $this->buyDateTBBull = $buyDateTBBull;
    }

    function setStrawsQuantityTBBull($strawsQuantityTBBull) {
        $this->strawsQuantityTBBull = $strawsQuantityTBBull;
    }

    function setStrawsPriceTBBull($strawsPriceTBBull) {
        $this->strawsPriceTBBull = $strawsPriceTBBull;
    }

}
