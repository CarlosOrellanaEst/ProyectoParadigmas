<?php

include_once '../data/PhotoData.php';

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
}