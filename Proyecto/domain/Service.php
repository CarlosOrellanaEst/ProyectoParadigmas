<?php

class Service {
    private $idTBService;
    private $nameTBService;
    private $photoURLTBService;
    private $statusTBService;

    function __construct($idTBService = 0, $nameTBService = "", $photoURLTBService = "", $statusTBService = 1) { 
        $this->idTBService = $idTBService;
        $this->nameTBService = $nameTBService;
        $this->photoURLTBService = $photoURLTBService;
        $this->statusTBService = $statusTBService;
    }

    public function getIdTBService() {
        return $this->idTBService;
    }
    public function getNameTBService() {
        return $this->nameTBService;
    }
    public function getPhotoURLTBService() {
        return $this->photoURLTBService;
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
        $this->photoURLTBService = $photoURLTBService;
    }
    public function setStatusTBService($statusTBService) {
        $this->statusTBService = $statusTBService;
    }
}

?>