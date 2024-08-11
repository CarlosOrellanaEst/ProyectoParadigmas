<?php

include '../data/ownerData.php';

class OwnerBusiness {

    private $ownerData;

    public function __construct() {
        $this->ownerData = new ownerData();
    }
    public function insertTBOwner($owner) {
        return $this->ownerData->insertTBOwner($owner);
    }
    public function getAllTBOwner() {
        return $this->ownerData->getAllTBOwner();
    }
    public function getTBOwner($idOwner) {
        return $this->ownerData->getTBOwner($idOwner);
    }

    public function updateTBOwner($owner) {
        return $this->ownerData->updateTBOwner($owner);
    }
    public function deleteTBOwner($idOwner) {
        return $this->ownerData->deleteTBOwner($idOwner);
    }

    public function getAllTBOwners() {
        return $this->ownerData->getAllTBOwners();
    }
    
}