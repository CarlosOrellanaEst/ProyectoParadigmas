<?php
class TouristCompanyBusiness {
    private $touristCompanyData;

    public function __construct() {
        $this->touristCompanyData = new TouristCompanyData();
    }

    public function createTouristCompany($touristCompany) {
        return $this->touristCompanyData->insertTouristCompany($touristCompany);
    }

    public function getTouristCompanies() {
        return $this->touristCompanyData->getAllTouristCompanies();
    }

    public function updateTouristCompany($touristCompany) {
        return $this->touristCompanyData->updateTouristCompany($touristCompany);
    }

    public function deleteTouristCompany($id) {
        return $this->touristCompanyData->deleteTouristCompany($id);
    }
}
