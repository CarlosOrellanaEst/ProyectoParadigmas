<?php

include '../data/serviceCompanyData.php';
include_once 'touristCompanyBusiness.php';
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
    public function getTBServicesByIds($ids) {
        return $this->serviceCompanyData->getTBServices($ids);
    }
    public function getAllTBServiceCompanies(){
        return $this->serviceCompanyData->getAllTBServiceCompanies();
    }

    public function getAllTBServiceCompaniesByOwner($idOwner){
        return $this->serviceCompanyData->getAllTBServiceCompaniesByOwner($idOwner);
    }

    // filtro para actividades
    public function getAllTBServiceCompaniesByOwnerForActivity($idOwner) {
        $allServicesOwner = "";
        $touristCompanyBusiness = new touristCompanyBusiness();

        // Obtener los touristcompany por un owner
        $allTouristCompaniesByOwner = $touristCompanyBusiness->getAllByOwnerID($idOwner);
       // por cada company ir a revisar la tabla tbservicecompany específicamente en su columna tbserviceid . De manera que me traiga todos los tbserviceid , los cuales van a venir en un String separados por coma. 
        if (count($allTouristCompaniesByOwner) > 0) {
            foreach ($allTouristCompaniesByOwner as $current) {
                $allServicesOwner .= $this->serviceCompanyData->getServicesIDsByCompanyID($current->getTbtouristcompanyid()) + ",";
            }
        }
       // recorriendo ese string separado por coma, llamo al método de tbservice que se trae todos los services 
        $allServicesOwnerDef = [];
        if ($allServicesOwner != "") {
            $allServicesOwnerDef = $this->getTBServicesByIds($allServicesOwner);
        }

        foreach ($allServicesOwnerDef as $current) { 
            echo ($current->getIdTbservice() . $current->getTbservicedescription());
        }


        return $allServicesOwnerDef;
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
        return $this->serviceCompanyData->removeServiceFromServiceCompany($serviceCompanyId, $serviceIdToRemove);
    }

    public function companyWithServices($companyID) {
        return $this->serviceCompanyData->companyWithServices($companyID);
    }
}
