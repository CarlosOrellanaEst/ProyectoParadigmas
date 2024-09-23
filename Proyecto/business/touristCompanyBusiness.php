<?php

include_once '../data/touristCompanyData.php';
include_once '../domain/TouristCompany.php';
class TouristCompanyBusiness {

    private $touristCompanyData;

    public function __construct() {
        $this->touristCompanyData = new TouristCompanyData();
    }

    public function insert($touristCompany) {
        return $this->touristCompanyData->insertTouristCompany($touristCompany);
    }

    public function getAll() {
        return $this->touristCompanyData->getAllTouristCompanies();
    }
    
    public function getAllByOwnerID($ownerID) {
        return $this->touristCompanyData->getAllTouristCompaniesByOwnerId($ownerID);
    }

    public function delete($idTouristCompany) {
        return $this->touristCompanyData->deleteTouristCompany($idTouristCompany);
    }

    public function update($touristCompany) {
        return $this->touristCompanyData->updateTouristCompany($touristCompany);
    }

    public function getById($idTouristCompany) {
        return $this->touristCompanyData->getTouristCompany($idTouristCompany);
    }

    public function getByName($touristCompanyName) {
        return $this->touristCompanyData->getTouristCompanyByName($touristCompanyName);
    }
    
    public function removeImageFromCompany($companyId, $imageToDelete){
        return $this->touristCompanyData->removeImageFromCompany($companyId, $imageToDelete);
    }

    public function isImageInUse($imageToDelete){
        return $this->touristCompanyData->isImageInUse($imageToDelete);
    }
}
