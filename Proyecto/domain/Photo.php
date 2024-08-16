<?php

class Photo {
    private $idTBPhoto;
    private $urlTBPhoto;
    private $statusTBPhoto;
   

    function __construct ($idTBPhoto = 0, $urlTBPhoto = "", $statusTBPhoto=true) { 
        $this->idTBPhoto = $idTBPhoto;
        $this->urlTBPhoto = $urlTBPhoto;
        $this->statusTBPhoto = $statusTBPhoto;
     
    }

     // Getters
     public function getIdTBPhoto() {
        return $this->idTBPhoto;
    }

    public function getUrlTBPhoto() {
        return $this->urlTBPhoto;
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

    public function setStatusTBPhoto($statusTBPhoto) {
        $this->statusTBPhoto = $statusTBPhoto;
    }
}
