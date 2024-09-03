<?php

class Service {
    private $idTBService;
    private $nameTBService;
    private $photoURLTBServiceArray;
    private $statusTBService;

    function __construct($idTBService, $nameTBService, $photoURLTBService, $statusTBService) { 
        $this->idTBService = $idTBService;
        $this->nameTBService = $nameTBService;
        $this->photoURLTBServiceArray = $photoURLTBService;
        $this->statusTBService = $statusTBService;
    }

    public function getIdTBService() {
        return $this->idTBService;
    }
    public function getNameTBService() {
        return $this->nameTBService;
    }
    public function getPhotoURLTBService() {
        return $this->photoURLTBServiceArray;
    }
    public function getStatusTBService() {
        return $this->statusTBService;
    }

    public function setIdTBService($idTBService) {
        $this->idTBService = $idTBService;
    }
    public function setNameTBService($nameTBService) {
        $this->nameTBService = $nameTBService;
    }
    public function setPhotoURLTBService($photoURLTBService) {
        $this->photoURLTBServiceArray = $photoURLTBService;
    }
    public function setStatusTBService($statusTBService) {
        $this->statusTBService = $statusTBService;
    }
}

?>