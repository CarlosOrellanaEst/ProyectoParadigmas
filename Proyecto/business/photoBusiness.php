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
   
    
}