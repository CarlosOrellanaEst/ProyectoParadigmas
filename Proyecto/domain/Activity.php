<?php

class Photo {
    private $idTBActivity;
    private $nameTBActivity;
    private $attributeTBActivity;
    private $indexTBActivity;
    private $dataAttributeTBActivity;
    private $statusTBActivity;

    function __construct ($idTBActivity = 0, $nameTBActivity = "", $attributeTBActivity="", $indexTBActivity="", $dataAttributeTBActivity="",$statusTBActivity=true) { 
        $this->idTBActivity = $idTBActivity;
        $this->nameTBActivity = $nameTBActivity;
        $this->attributeTBActivity = $attributeTBActivity;
        $this->indexTBActivity = $indexTBActivity;
        $this->dataAttributeTBActivity = $dataAttributeTBActivity;
        $this->statusTBActivity = $statusTBActivity;
    }

    // Getters
    public function getIdTBPhoto() {
        return $this->idTBPhoto;
    }

    public function getUrlTBPhoto() {
        return $this->urlTBPhoto;
    }

    public function getIndexTBPhoto() {
        return $this->indexTBPhoto;
    }

    public function getStatusTBPhoto() {
        return $this->statusTBPhoto;
    }

    // Setters
    public function setIdTBPhoto($idTBPhoto) {
        $this->idTBPhoto = $idTBPhoto;
    }

    public function setUrlTBPhoto($urlTBPhoto) {
        $this->urlTBPhoto = $urlTBPhoto;
    }

    public function setIndexTBPhoto($indexTBPhoto) {
        $this->indexTBPhoto = $indexTBPhoto;
    }

    public function setStatusTBPhoto($statusTBPhoto) {
        $this->statusTBPhoto = $statusTBPhoto;
    }
}