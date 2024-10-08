<?php

include '../data/serviceCompanyData.php';
include 'photoBusiness.php';

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
    public function getServiceCompany($serviceCompanyId)  {
        return $this->serviceCompanyData->getServiceCompany($serviceCompanyId) ;
    }
    public function getTBService($idService) {
        return $this->serviceCompanyData->getTBService($idService);
    }
    public function getAllTBServiceCompanies(){
        return $this->serviceCompanyData->getAllTBServiceCompanies();
    }
    public function removeImageFromServiceCompany($serviceCompanyId, $newImageUrls){
        return $this->serviceCompanyData->removeImageFromServiceCompany($serviceCompanyId, $newImageUrls);
    }
    public function deleteTBServiceCompany($idService) {
        return $this->serviceCompanyData->deleteTBServiceCompany($idService);
    }
    public function updateTBServiceCompany($service) {
        return $this->serviceCompanyData->updateTBServiceCompany($service);
    }

    public function removeServiceFromServiceCompany($serviceCompanyId, $serviceIdToRemove){
        return $this->serviceCompanyData->removeServiceFromServiceCompany($serviceCompanyId, $service);
    }
}
