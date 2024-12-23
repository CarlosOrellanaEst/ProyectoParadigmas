<?php
include_once '../data/ownerData.php';

class OwnerBusiness {

    private $ownerData;

    public function __construct() {
        $this->ownerData = new ownerData();
    }
    public function insertTBOwner($owner) {
        return $this->ownerData->insertTBOwner($owner);
    }

    public function getTBOwnerByPhone($phone) {
        return $this->ownerData->getTBOwnerByPhone($phone);
    }
    public function getAllTBOwner() {
        return $this->ownerData->getAllTBOwner();
    }
    public function getTBOwner($idOwner) {
        return $this->ownerData->getTBOwner($idOwner);
    }
    public function getTBOwnerByUserId($idUser) {
        return $this->ownerData->getTBOwnerByUserId($idUser);
    }
    
    public function updateTBOwner($owner) {
        return $this->ownerData->updateTBOwner($owner);
    }
    public function deleteTBOwner($idOwner, $idUser) {
     //   echo($idOwner.$idUser);
        return $this->ownerData->deleteTBOwner($idOwner, $idUser);
    }

    public function getAllTBOwners() {
        return $this->ownerData->getAllTBOwner();
    }
    
}