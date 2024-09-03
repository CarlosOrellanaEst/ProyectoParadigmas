<?php

include_once '../data/photoData.php';

class PhotoBusiness {

    private $photoData;

    public function __construct() {
        $this->photoData = new PhotoData();
    }
    public function insertMultiplePhotos($photo) {
        return $this->photoData->insertMultiplePhotos($photo);
    }
    public function getAllTBPhotos() {
        return $this->photoData->getAllTBPhotos();
    }
    
    public function updateTBPhoto($photoId, $imageIndex, $newUrl, $existingUrls) {
        return $this->photoData->updateTBPhoto($photoId, $imageIndex, $newUrl, $existingUrls);
    }
    public function deleteTBPhoto($photoId, $imageIndex) {
        return $this->photoData->deleteTBPhoto($photoId, $imageIndex);
    }
    /*
    public function getLastInsertedPhotoId() {
        return $this->photoData->getLastInsertedPhotoId();
    }
        */
}