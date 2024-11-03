<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once '../business/touristCompanyBusiness.php';
include_once '../domain/TouristCompany.php';
include_once '../domain/Owner.php';
include_once '../domain/TouristCompanyType.php';
include_once '../business/ownerBusiness.php';
include_once '../business/touristCompanyTypeBusiness.php';
include_once '../business/photoBusiness.php';
header('Content-Type: application/json; charset=utf-8');


$response = array();

if (isset($_POST['create'])) {
    $uploadDir = '../images/';
    $fileNames = array();
    $allowTypes = array('jpg', 'png', 'jpeg', 'gif');

    if (isset($_FILES['imagenes']) && !empty($_FILES['imagenes']['name'][0])) {
        if (count($_FILES['imagenes']['name']) > 5) {
            echo json_encode(['status' => 'error', 'error_code' => 'max_images_exceeded', 'message' => 'Solo se permite subir un máximo de 5 imágenes']);
            exit();
        }

        foreach ($_FILES['imagenes']['name'] as $key => $fileName) {
            $targetFilePath = $uploadDir . basename($fileName);
            $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

            if (in_array($fileType, $allowTypes)) {
                if (move_uploaded_file($_FILES['imagenes']['tmp_name'][$key], $targetFilePath)) {
                    $fileNames[] = basename($fileName);
                } else {
                    echo json_encode(['status' => 'error', 'error_code' => 'file_move_failed', 'message' => 'Error al mover la imagen al directorio.']);
                    exit();
                }
            } else {
                echo json_encode(['status' => 'error', 'error_code' => 'invalid_file_type', 'message' => 'Formato de imagen inválido. Solo se permiten JPG, PNG, JPEG y GIF.']);
                exit();
            }
        }
    }

    $photoUrls = !empty($fileNames) ? implode(',', $fileNames) : null;

    $legalName = $_POST['legalName'] ?? '';
    $magicName = $_POST['magicName'] ?? '';
    $ownerId = $_POST['ownerId'] ?? 0;
    $companyTypeId = $_POST['companyType'] ?? 0;
    $status = $_POST['status'] ?? '';
    $customCompanyType = '';
    $selectedCompanyTypes = $_POST['selectedCompanyTypes'] ?? [];
    echo '<script>console.log(' . json_encode($selectedCompanyTypes) . ')</script>';
    if (empty($selectedCompanyTypes)) {
        echo json_encode(['status' => 'error', 'error_code' => 'no_company_types', 'message' => 'No se han seleccionado tipos de empresa.']);
        exit();
    }
    

    if ($companyTypeId === '0') {
        $customCompanyType = $_POST['customCompanyType'] ?? '';
        if (empty($customCompanyType)) {
            echo json_encode(['status' => 'error', 'error_code' => 'custom_company_type_required', 'message' => 'Debe especificar un tipo de empresa personalizado.']);
            exit();
        }
        /*else {
            // Aquí debes insertar el tipo de empresa personalizado en la tabla correspondiente

            $customTypeBusiness = new TouristCompanyData(); // Asegúrate de que esta clase esté bien incluida
            $result = $customTypeBusiness->insertCustomizedtouristcompanytype($ownerId, $customCompanyType);
            // Agrega un mensaje de depuración
            if ($result['status'] !== 'success') {
                echo json_encode(['status' => 'error', 'error_code' => 'insert_custom_type_failed', 'message' => $result['message']]);
                exit();
            }
            // Si se inserta correctamente, puedes obtener el ID del tipo de empresa personalizado

            $companyTypeId = $result['new_id']; // Asegúrate de que tu método de inserción devuelva el nuevo ID
        }*/
    }

    if ($ownerId) {
        $ownerBusiness = new OwnerBusiness();
        $owner = $ownerBusiness->getTBOwner($ownerId);

        $touristCompanyTypeBusiness = new TouristCompanyTypeBusiness();
        $companyType = $touristCompanyTypeBusiness->getById($companyTypeId);

        if ($owner && $companyType) {
            $touristCompany = new TouristCompany(0, $legalName, $magicName, $ownerId, $companyTypeId, $photoUrls, $status);
            $touristCompany->setAllTouristCompanyType($selectedCompanyTypes ?? []);

            $touristCompanyBusiness = new TouristCompanyBusiness();
            $result = $touristCompanyBusiness->insert($touristCompany);

            if ($companyTypeId === '0') {
                $touristCompany->setTbtouristcompanycustomcompanyType($customCompanyType);
                $bol = $touristCompanyBusiness->insertCustomizedtouristcompanytype($ownerId, $customCompanyType);
            }

            if ($result['status'] == 'success') {
                ob_end_clean();
                echo json_encode(['status' => 'success', 'message' => 'Empresa creada con éxito.'], JSON_UNESCAPED_UNICODE);
                exit;
            } elseif ($result['status'] == 'error' && isset($result['message']) && $result['message'] === 'Empresa ya existe.') {
                echo json_encode(['status' => 'error', 'error_code' => 'company_exists', 'message' => 'La empresa ya existe.']);
            } else {
                echo json_encode(['status' => 'error', 'error_code' => 'database_error', 'message' => 'Error en la base de datos: ' . $result['message']]);
            }
        } else {
            echo json_encode(['status' => 'error', 'error_code' => 'invalid_owner_or_company_type', 'message' => 'Propietario o tipo de compañía inválido.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'error_code' => 'owner_required', 'message' => 'El campo propietario es obligatorio.']);
    }

    exit();
}

if (isset($_POST['update'])) {
    if (isset($_POST['id'], $_POST['ownerId'], $_POST['status'])) {
        $id = $_POST['id'];
        $legalName = $_POST['legalName'];
        $magicName = $_POST['magicName'];
        $ownerId = $_POST['ownerId'];
        $companyTypeId = $_POST['companyType'];
        $status = $_POST['status'];

        $photoFileName = '';
        $touristCompanyBusiness = new TouristCompanyBusiness();
        $currentTouristCompany = $touristCompanyBusiness->getById($id);
        $existingPhotoFileName = $currentTouristCompany->getTbtouristcompanyurl();

        if (isset($_FILES['newImage']) && $_FILES['newImage']['error'] == UPLOAD_ERR_OK) {
            $uploadDir = '../images/';
            $fileName = basename($_FILES['newImage']['name']);
            $targetFilePath = $uploadDir . $fileName;
            $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
            $allowTypes = array('jpg', 'png', 'jpeg', 'gif');

            if (in_array($fileType, $allowTypes)) {
                if (move_uploaded_file($_FILES['newImage']['tmp_name'], $targetFilePath)) {
                    $photoFileName = $fileName;
                } else {
                    echo json_encode(['status' => 'error', 'error_code' => 'upload_failed', 'message' => 'Error al subir la imagen.']);
                    exit();
                }
            } else {
                echo json_encode(['status' => 'error', 'error_code' => 'invalid_file_type', 'message' => 'Formato de imagen inválido. Solo se permiten JPG, PNG, JPEG y GIF.']);
                exit();
            }
        } else {
            $photoFileName = $existingPhotoFileName;
        }

        if ($ownerId) {
            $touristCompany = new TouristCompany($id, $legalName, $magicName, $ownerId, $companyTypeId, $photoFileName, $status);
            $result = $touristCompanyBusiness->update($touristCompany);

            if ($result['status'] === 'success') {
                echo json_encode(['status' => 'success', 'message' => 'Empresa actualizada con éxito.']);
            } elseif ($result['status'] === 'error' && strpos($result['message'], 'Ya existe una compañía turística') !== false) {
                echo json_encode(['status' => 'error', 'error_code' => 'company_exists', 'message' => 'Ya existe una empresa turística con el mismo nombre legal.']);
            } else {
                echo json_encode(['status' => 'error', 'error_code' => 'update_failed', 'message' => 'Error al actualizar la empresa.']);
            }
        } else {
            echo json_encode(['status' => 'error', 'error_code' => 'invalid_fields', 'message' => 'Campos inválidos en la actualización.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'error_code' => 'missing_fields', 'message' => 'Faltan campos obligatorios para la actualización.']);
    }

    exit();
}

if (isset($_POST['delete'])) {
    if (isset($_POST['id']) && is_numeric($_POST['id'])) {
        $id = $_POST['id'];
        $touristCompanyBusiness = new TouristCompanyBusiness();
        $result = $touristCompanyBusiness->delete($id);

        if ($result) {
            echo json_encode(['status' => 'success', 'message' => 'Empresa eliminada con éxito.']);
        } else {
            echo json_encode(['status' => 'error', 'error_code' => 'delete_failed', 'message' => 'Error al eliminar la empresa.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'error_code' => 'invalid_id', 'message' => 'ID inválido para la eliminación.']);
    }

    exit();
}

if (isset($_POST['deleteImage'])) {
    $companyId = $_POST['photoID'];
    $imageIndexToDelete = (int) $_POST['imageIndex'];

    $touristCompanyBusiness = new TouristCompanyBusiness();
    $currentTouristCompany = $touristCompanyBusiness->getById($companyId);

    $images = $currentTouristCompany->getTbtouristcompanyurl();

    if (isset($images[$imageIndexToDelete])) {
        $filePath = '../images/' . trim($images[$imageIndexToDelete]);
        $imageToDelete = trim($images[$imageIndexToDelete]);

        unset($images[$imageIndexToDelete]);
        $newImageUrls = implode(',', $images);
        $touristCompanyBusiness->removeImageFromCompany($companyId, $newImageUrls);

        $imageInUse = $touristCompanyBusiness->isImageInUse($imageToDelete);

        if (!$imageInUse && file_exists($filePath)) {
            unlink($filePath);
        }

        echo json_encode(['status' => 'success', 'message' => 'Imagen eliminada con éxito.']);
    } else {
        echo json_encode(['status' => 'error', 'error_code' => 'image_not_found', 'message' => 'Imagen no encontrada.']);
    }

    exit();
}

