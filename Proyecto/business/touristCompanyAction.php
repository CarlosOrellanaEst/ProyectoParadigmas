<?php

include_once '../business/touristCompanyBusiness.php';
include_once '../domain/TouristCompany.php';
include_once '../domain/Owner.php'; // Asegúrate de incluir el archivo correcto para la clase Owner
include_once '../domain/TouristCompanyType.php'; // Asegúrate de incluir el archivo correcto para la clase CompanyType
include_once '../business/OwnerBusiness.php'; // Asegúrate de incluir el archivo correcto para la clase OwnerBusiness
include_once '../business/touristCompanyTypeBusiness.php'; // Asegúrate de incluir el archivo correcto para la clase touristCompanyTypeBusiness
include_once '../business/photoBusiness.php'; // Incluye el archivo que define PhotoBusiness

header('Content-Type: application/json');

if (isset($_POST['create'])) {
    $response = array();
    if (isset($_FILES['imagenes']) && !empty($_FILES['imagenes']['name'][0])) {
        $uploadDir = '../images/';
        $fileNames = array();
        $allowTypes = array('jpg', 'png', 'jpeg', 'gif');

        // Verifica que no se suban más de 5 archivos
        if (count($_FILES['imagenes']['name']) > 5) {
            $response['status'] = 'error';
            $response['message'] = 'Se permite máximo 5 imágenes';
            echo json_encode($response);
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
                    $response['status'] = 'error';
                    $response['message'] = 'Error al mover la imagen';
                    echo json_encode($response);
                    exit();
                }
            } else {
                $response['status'] = 'error';
                $response['message'] = 'Formato de imagen inválido';
                echo json_encode($response);
                exit();
            }
        }

        // Insertar las fotos en la base de datos
        $photoUrls = implode(',', $fileNames);
        
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

                $touristCompanyTypeBusiness = new TouristCompanyTypeBusiness();
                $companyType = $touristCompanyTypeBusiness->getById($companyTypeId);

                if ($owner && $companyType) {
                    $touristCompany = new TouristCompany(0, $legalName, $magicName, $ownerId, $companyTypeId, $photoUrls, $status);
                    $touristCompanyBusiness = new TouristCompanyBusiness();
                    $result = $touristCompanyBusiness->insert($touristCompany);

                    // Verificación del resultado de la inserción
                    if ($result['status'] == 'success') {
                        $response = ['status' => 'success', 'message' => 'Empresa creada con éxito.'];
                    } elseif ($result['status'] == 'error' && isset($result['message']) && $result['message'] === 'Empresa ya existe.') {
                        $response = ['status' => 'error', 'message' => 'Empresa ya existe.'];
                    } else {
                        $response = ['status' => 'error', 'message' => 'Error en la base de datos: ' . $result['message']];
                    }
                } else {
                    $response = ['status' => 'error', 'message' => 'Propietario o tipo de compañía inválido.'];
                }
            } else {
                $response = ['status' => 'error', 'message' => 'Formato de datos inválido.'];
            }
        } else {
            $response = ['status' => 'error', 'message' => 'Los campos no deben estar vacíos.'];
        }

        echo json_encode($response);
        exit();
    } else {
        $response = ['status' => 'error', 'message' => 'No se han subido imágenes.'];
        echo json_encode($response);
        exit();
    }
}

if (isset($_POST['update'])) {
    if (isset($_POST['id']) && isset($_POST['ownerId']) && isset($_POST['legalName']) && isset($_POST['magicName']) && isset($_POST['companyType']) && isset($_POST['status'])) {
        
        $id = $_POST['id'];
        $legalName = $_POST['legalName'];
        $magicName = $_POST['magicName'];
        $ownerId = $_POST['ownerId'];
        $companyTypeId = $_POST['companyType'];
        $status = $_POST['status'];

        // Variable para almacenar el nombre del archivo de la imagen
        $photoFileName = '';

        // Obtener la URL actual de la foto de la empresa turística
        $touristCompanyBusiness = new TouristCompanyBusiness();
        $currentTouristCompany = $touristCompanyBusiness->getById($id);
        $existingPhotoFileName = $currentTouristCompany->getTbtouristcompanyurl(); // Asumimos que la URL de las imágenes se almacena aquí

        // Verificar si se ha subido una nueva imagen
        if (isset($_FILES['newImage']) && $_FILES['newImage']['error'] == UPLOAD_ERR_OK) {
            $uploadDir = '../images/';
            $fileName = basename($_FILES['newImage']['name']);
            $targetFilePath = $uploadDir . $fileName;

            $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
            $allowTypes = array('jpg', 'png', 'jpeg', 'gif');

            // Validar el tipo de archivo y mover la imagen si es válido
            if (in_array($fileType, $allowTypes)) {
                if (move_uploaded_file($_FILES['newImage']['tmp_name'], $targetFilePath)) {
                    $photoFileName = $fileName;
                } else {
                    header("location: ../view/touristCompanyView.php?error=uploadFailed");
                    exit();
                }
            } else {
                header("location: ../view/touristCompanyView.php?error=invalidFileType");
                exit();
            }
        } else {
            // Si no se sube una nueva imagen, se usa la existente
            $photoFileName = $existingPhotoFileName;
        }

        // Validación básica
        if (strlen(trim($legalName)) > 0 && strlen(trim($magicName)) > 0 && is_numeric($ownerId) && is_numeric($companyTypeId) && is_numeric($status)) {
            if (!is_numeric($legalName) && !is_numeric($magicName)) {
                $ownerBusiness = new OwnerBusiness();
                $owner = $ownerBusiness->getTBOwner($ownerId);

                $touristCompanyTypeBusiness = new TouristCompanyTypeBusiness();
                $companyType = $touristCompanyTypeBusiness->getById($companyTypeId);

                if ($owner && $companyType) {
                    // Crear el objeto TouristCompany con la imagen actualizada (o la existente)
                    $touristCompany = new TouristCompany($id, $legalName, $magicName, $ownerId, $companyTypeId, $photoFileName, $status);

                    $result = $touristCompanyBusiness->update($touristCompany);

                    if ($result) {
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
