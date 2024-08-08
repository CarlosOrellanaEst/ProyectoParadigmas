<?php

include '../data/ownerData.php';

class OwnerBusiness {
    private $ownerData;

    public function __construct() {
        $this->ownerData = new OwnerData(); 
    }

    public function getAllTBOwners() {
        return $this->ownerData->getAllTBOwners();
    }
}
