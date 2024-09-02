<?php

include_once '../business/touristCompanyBusiness.php';
include_once '../domain/TouristCompany.php';
include_once '../domain/owner.php'; // Asegúrate de incluir el archivo correcto para la clase Owner
include_once '../domain/TouristCompanyType.php'; // Asegúrate de incluir el archivo correcto para la clase CompanyType
include_once '../business/OwnerBusiness.php'; // Asegúrate de incluir el archivo correcto para la clase OwnerBusiness
include_once '../business/touristCompanyTypeBusiness.php'; // Asegúrate de incluir el archivo correcto para la clase touristCompanyTypeBusiness
include_once '../business/PhotoBusiness.php'; // Incluye el archivo que define PhotoBusiness

header('Content-Type: application/json');

if (isset($_POST['create'])) {
    if (isset($_FILES['imagenes']) && !empty($_FILES['imagenes']['name'][0])) {
        $uploadDir = '../images/';
        $fileNames = array();
        $allowTypes = array('jpg', 'png', 'jpeg', 'gif');

        // Verifica que no se suban más de 5 archivos
        if (count($_FILES['imagenes']['name']) > 5) {
            header("location: ../view/photoView.php?error=tooManyFiles");
            exit();
        }

        // Mover los archivos y obtener los nombres
        foreach ($_FILES['imagenes']['name'] as $key => $fileName) {
            $targetFilePath = $uploadDir . basename($fileName);
            $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

            if (in_array($fileType, $allowTypes)) {
                $tempPath = $_FILES['imagenes']['tmp_name'][$key];
                if (move_uploaded_file($tempPath, $targetFilePath)) {
                    $fileNames[] = basename($fileName);
                } else {
                    header("location: ../view/photoView.php?error=moveFailed");
                    exit();
                }
            } else {
                header("location: ../view/photoView.php?error=invalidFileType");
                exit();
            }
        }

       // Insertar las fotos en la base de datos
$photoUrls = implode(',', $fileNames);
$photoBusiness = new PhotoBusiness();
$lastPhotoId = $photoBusiness->insertMultiplePhotos($photoUrls);

if ($lastPhotoId !== false) {
    // Aquí el valor de $lastPhotoId ya es el último ID insertado correctamente
    // Puedes proceder con la inserción de la compañía turística usando $lastPhotoId

    // Obtener datos de la empresa turística
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
                $touristCompany = new TouristCompany(0, $legalName, $magicName, $ownerId, $companyTypeId, $lastPhotoId, $status);
                $touristCompanyBusiness = new touristCompanyBusiness();
                $result = $touristCompanyBusiness->insert($touristCompany, $lastPhotoId);

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
} else {
    $response = ['status' => 'error', 'message' => 'Failed to insert photos.'];
}

echo json_encode($response);
    }
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