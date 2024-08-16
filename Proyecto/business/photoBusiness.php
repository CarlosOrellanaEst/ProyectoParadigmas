<?php

include '../data/photoData.php';

class photoBusiness {

    private $photoData;

    public function __construct() {
        $this->photoData = new photoData();
    }
    public function insertTBPhoto($photo) {
        return $this->photoData->insertTBPhoto($photo);
    }
   
    public function getAllTBPhotos() {
        return $this->photoData->getAllTBPhotos();
    }

    public function updateTBPhoto($photo) {
        return $this->photoData->updateTBPhoto($photo);
    }
    public function deleteTBPhoto($idPhoto) {
        return $this->photoData->deleteTBPhoto($idPhoto);
    }
}