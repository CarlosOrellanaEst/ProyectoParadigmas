<?php

class Photo {
    private $idTBPhoto;
    private $urlTBPhoto;
    private $indexTBPhoto;
    private $statusTBPhoto;
    private $directoryTBPhoto;

    function __construct ($idTBPhoto = 0, $urlTBPhoto = "", $indexTBPhoto="", $statusTBPhoto=true) { 
        $this->idTBPhoto = $idTBPhoto;
        $this->urlTBPhoto = $urlTBPhoto;
        $this->indexTBPhoto = $indexTBPhoto;
        $this->statusTBPhoto = $statusTBPhoto;
     
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