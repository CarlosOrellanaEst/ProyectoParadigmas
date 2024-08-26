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
    } else {
        $response = ['status' => 'error', 'message' => 'Empty fields are not allowed.'];
    }
    echo json_encode($response);
    
}
/*
if (isset($_POST['update'])) {
    $id = $_POST['id'] ?? 0;
    $legalName = $_POST['legalName'] ?? '';
    $magicName = $_POST['magicName'] ?? '';
    $ownerId = $_POST['ownerId'] ?? 0;
    $companyTypeId = $_POST['companyType'] ?? 0;
    $status = $_POST['status'] ?? '';

    if (!empty($legalName) && !empty($magicName) && is_numeric($id) && is_numeric($ownerId) && is_numeric($companyTypeId)) {
        if (!is_numeric($legalName) && !is_numeric($magicName)) {
            $touristCompanyBusiness = new touristCompanyBusiness();
            $touristCompany = new TouristCompany($id, $legalName, $magicName, $ownerId, $companyTypeId, $status);
            $result = $touristCompanyBusiness->update($touristCompany);

            if ($result) {
                $response = ['status' => 'success', 'message' => 'Empresa actualizada correctamente.'];
            } else {
                $response = ['status' => 'error', 'message' => 'No se pudo actualizar la empresa.'];
            }
        } else {
            $response = ['status' => 'error', 'message' => 'El nombre legal y mágico no deben ser numéricos.'];
        }
    } else {
        $response = ['status' => 'error', 'message' => 'Datos inválidos o faltantes.'];
    }
    echo json_encode($response);
    exit();
} else if (isset($_POST['delete'])) {
    if (isset($_POST['id'])) {
        $id = $_POST['id'];

        if (is_numeric($id)) {
            $touristCompanyBusiness = new touristCompanyBusiness();
            $result = $touristCompanyBusiness->delete($id);

            if ($result == 1) {
                $response = ['status' => 'success', 'message' => 'Empresa eliminada correctamente.'];
            } else {
                $response = ['status' => 'error', 'message' => 'No se pudo eliminar la empresa.'];
            }
        } else {
            $response = ['status' => 'error', 'message' => 'ID inválido.'];
        }
    } else {
        $response = ['status' => 'error', 'message' => 'ID faltante.'];
    }
    echo json_encode($response);
    exit();
} else {
    $response = ['status' => 'error', 'message' => 'Acción no definida.'];
    echo json_encode($response);
    exit();
}
    */