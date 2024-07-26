<?php

include '../data/bullData.php';

class BullBusiness {

    private $bullData;

    public function BullBusiness() {
        $this->bullData = new BullData();
    }

    public function insertTBBull($bull) {
        return $this->bullData->insertTBBull($bull);
    }

    public function updateTBBull($bull) {
        return $this->bullData->updateTBBull($bull);
    }

    public function deleteTBBull($idBull) {
        return $this->bullData->deleteTBBull($idBull);
    }

    public function getAllTBBull() {
        return $this->bullData->getAllTBBull();
    }
    
    public function getBullsInventary() {
        return $this->bullData->getBullsInventary();
    }
    
}