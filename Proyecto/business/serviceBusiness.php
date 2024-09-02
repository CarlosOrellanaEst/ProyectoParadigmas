<?php

include '../data/serviceData.php';
include 'PhotoBusiness.php';

class ServiceBusiness {

    private $serviceData;

    public function __construct() {
        $this->serviceData = new ServiceData();
    }
    public function insertTBService($service) {
        return $this->serviceData->insertTBService($service);
    }
    public function getAllTBServices() {
        $services = $this->serviceData->getAllTBServices();
        foreach ($services as $currentService) {
            
        }
    }
    /* 
    public function getOneTBService($idService) {
        return $this->serviceData->getTBRoll($idRoll);
    }

    public function updateTBService($service) {
        return $this->serviceData->updateTBRoll($roll);
    }
    public function deleteTBService($idService) {
        return $this->serviceData->deleteTBRoll($idRoll);
    } */
    
}