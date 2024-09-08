<?php

include '../data/serviceCompanyData.php';
include 'PhotoBusiness.php';

class serviceCompanyBusiness {

    private $serviceCompanyData;

    public function __construct() {
        $this->serviceCompanyData = new serviceCompanyData();
    }
    public function insertTBServiceCompany($service) {
        return $this->serviceCompanyData->insertTBServiceCompany($service);
    }
    public function getAllTBServices() {
        return $this->serviceCompanyData->getAllTBServices();
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
