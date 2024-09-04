<?php

include_once '../business/touristCompanyBusiness.php';
include_once '../domain/TouristCompany.php';
include_once '../domain/owner.php'; // Asegúrate de incluir el archivo correcto para la clase Owner
include_once '../domain/TouristCompanyType.php'; // Asegúrate de incluir el archivo correcto para la clase CompanyType
include_once '../business/OwnerBusiness.php'; // Asegúrate de incluir el archivo correcto para la clase OwnerBusiness
include_once '../business/touristCompanyTypeBusiness.php'; // Asegúrate de incluir el archivo correcto para la clase touristCompanyTypeBusiness

header('Content-Type: application/json');

$response = ['status' => 'error', 'message' => 'An unknown error occurred.'];

if (isset($_POST['create'])) {
    $legalName = $_POST['legalName'] ?? '';
    $magicName = $_POST['magicName'] ?? '';
    $ownerId = $_POST['ownerId'] ?? 0;
    $companyTypeId = $_POST['companyType'] ?? 0;
    $status = $_POST['status'] ?? '';

    if (!empty($legalName) && !empty($magicName) && is_numeric($ownerId) && is_numeric($companyTypeId)) {
        if (!is_numeric($legalName) && !is_numeric($magicName)) {
            $ownerBusiness = new OwnerBusiness();
            $owner = $ownerBusiness->getTBOwner($ownerId);

            $touristCompanyTypeBusiness = new touristCompanyTypeBusiness();
            $companyType = $touristCompanyTypeBusiness->getById($companyTypeId);

            if ($owner && $companyType) {
                $touristCompany = new TouristCompany(0, $legalName, $magicName, $ownerId, $companyTypeId, $status);
                $touristCompanyBusiness = new touristCompanyBusiness();
                $result = $touristCompanyBusiness->insert($touristCompany);

                if ($result == 1) {
                    $response = ['status' => 'success', 'message' => 'Company successfully created.'];
                    
                } elseif ($result === null) {
                    $response = ['status' => 'error', 'message' => 'Company already exists.'];
                } else {
                    $response = ['status' => 'error', 'message' => 'Database error.'];
                }
            } else {
                $response = ['status' => 'error', 'message' => 'Invalid owner or company type.'];
            }
        } else {
            $response = ['status' => 'error', 'message' => 'Invalid data format.'];
        }
<<<<<<< Updated upstream
    } else {
        $response = ['status' => 'error', 'message' => 'Empty fields are not allowed.'];
=======

        //echo json_encode($response);
        exit();
    } else {
        $response = ['status' => 'error', 'message' => 'No se han subido imágenes.'];
        //echo json_encode($response);
        exit();
>>>>>>> Stashed changes
    }
    echo json_encode($response);
    
}

if (isset($_POST['update'])) {
    if (isset($_POST['ownerId']) && isset($_POST['tbtouristcompanyid']) && isset($_POST['magicName']) && isset($_POST['ownerId']) && isset($_POST['companyType']) && isset($_POST['status'])) {
        
        $id = $_POST['id'];
        $tbtouristcompanyLegalName = $_POST['tbtouristcompanyid'];
        $magicName = $_POST['magicName'];
        $ownerId = $_POST['ownerId'];
        $companyTypeId = $_POST['companyType'];
        $status = $_POST['status'];
        
        // Validación de campos
        if (strlen(trim($tbtouristcompanyLegalName)) > 0 && strlen(trim($magicName)) > 0 && is_numeric($ownerId) && is_numeric($companyTypeId) && is_numeric($status)) {
            if (!is_numeric($tbtouristcompanyLegalName) && !is_numeric($magicName) &&  is_numeric($status)) {
                $ownerBusiness = new OwnerBusiness();
                $owner = $ownerBusiness->getTBOwner($ownerId);

                $touristCompanyTypeBusiness = new touristCompanyTypeBusiness();
                $companyType = $touristCompanyTypeBusiness->getById($companyTypeId);

                if ($ownerId && $companyType) {
                    $touristCompanyBusiness = new touristCompanyBusiness();
                    $touristCompany = new TouristCompany($id, $tbtouristcompanyLegalName, $magicName, $ownerId, $companyTypeId, $status);
                    $result = $touristCompanyBusiness->update($touristCompany);

                    if ($result == 1) {
                        header("location: ../view/touristCompanyView.php?success=updated");
                        exit();
                    } else {
                        header("location: ../view/touristCompanyView.php?error=updateFailed");
                        exit();
                    }
                } else {
                    header("location: ../view/touristCompanyView.php?error=invalidOwnerOrCompanyType");
                    exit();
                }
            } else {
                header("location: ../view/touristCompanyView.php?error=numberFormat");
                exit();
            }
        } else {
            header("location: ../view/touristCompanyView.php?error=emptyField");
            exit();
        }
    } else {
        header("location: ../view/touristCompanyView.php?error=missingFields");
        exit();
    }
}

if (isset($_POST['delete'])) {
    if (isset($_POST['id'])) {
        
        $id = $_POST['id'];
        
        // Validación de id
        if (is_numeric($id)) {
            $touristCompanyBusiness = new touristCompanyBusiness();
            $result = $touristCompanyBusiness->delete($id);

            if ($result == 1) {
                header("location: ../view/touristCompanyView.php?success=deleted");
                exit();
            } else {
                header("location: ../view/touristCompanyView.php?error=deleteFailed");
                exit();
            }
        } else {
            header("location: ../view/touristCompanyView.php?error=invalidId");
            exit();
        }
    } else {
        header("location: ../view/touristCompanyView.php?error=missingId");
        exit();
    }
}