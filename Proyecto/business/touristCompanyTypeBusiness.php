<?php

include_once  '../data/touristCompanyTypeData.php';
include_once  '../domain/touristCompanyType.php';

class touristCompanyTypeBusiness {
    private $touristCompanyType;

    public function __construct() {
        $this->touristCompanyType = new touristCompanyTypeData(); 
    }

    public function insert($companyType) {
        return $this->touristCompanyType->insertTbTouristCompanyType($companyType);
    }

    public function getAll() {
        return $this->touristCompanyType->getAllTbTouristCompanyType();
    }

    public function delete($idTouristCompanyType) {
        return $this->touristCompanyType->deleteTbTouristCompanyType($idTouristCompanyType);
    }

    public function update($TouristCompanyType) {
        return $this->touristCompanyType->updateTbTouristCompanyType($TouristCompanyType);
    }

    public function getById($idTouristCompanyType) {
        return $this->touristCompanyType->getTbTouristCompanyTypeById($idTouristCompanyType);
    }
}