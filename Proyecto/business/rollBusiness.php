<?php

include '../data/rollData.php';

class RollBusiness {

    private $rollData;

    public function __construct() {
        $this->rollData = new RollData();
    }
    public function insertTBRoll($roll) {
        return $this->rollData->insertTBRoll($roll);
    }
    public function getAllTBRolls() {
        return $this->rollData->getAllTBRolls();
    }
    public function getOneTBRoll($idRoll) {
        return $this->rollData->getTBRoll($idRoll);
    }

    public function updateTBRoll($roll) {
        return $this->rollData->updateTBRoll($roll);
    }
    public function deleteTBRoll($idRoll) {
        return $this->rollData->deleteTBRoll($idRoll);
    }
    
}