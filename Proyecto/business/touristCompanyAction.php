<?php
require_once 'touristCompanyBusiness.php';

if (isset($_POST['action'])) {
    $touristCompanyBusiness = new TouristCompanyBusiness();

    switch ($_POST['action']) {
        case 'create':
            $touristCompany = new TouristCompany(
                $_POST['id'],
                $_POST['legalName'],
                $_POST['magicName'],
                (new OwnerBusiness())->getOwnerById($_POST['ownerId']),
                (new CompanyTypeBusiness())->getCompanyTypeById($_POST['companyTypeId'])
            );
            $touristCompanyBusiness->createTouristCompany($touristCompany);
            break;

        case 'update':
            $touristCompany = new TouristCompany(
                $_POST['id'],
                $_POST['legalName'],
                $_POST['magicName'],
                (new OwnerBusiness())->getOwnerById($_POST['ownerId']),
                (new CompanyTypeBusiness())->getCompanyTypeById($_POST['companyTypeId'])
            );
            $touristCompanyBusiness->updateTouristCompany($touristCompany);
            break;

        case 'delete':
            $touristCompanyBusiness->deleteTouristCompany($_POST['id']);
            break;

        case 'getAll':
            echo json_encode($touristCompanyBusiness->getTouristCompanies());
            break;
    }
}
?>
