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
    public function updateTBRoll($roll) {
        return $this->rollData->updateTBRoll($roll);
    }
    public function deleteTBRoll($roll) {
        return $this->rollData->deleteTBRoll($roll);
    }
    
}