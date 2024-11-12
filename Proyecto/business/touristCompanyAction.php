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

    // Validación y carga de imágenes
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

    // Obtener las URLs de las imágenes
    $photoUrls = !empty($fileNames) ? implode(',', $fileNames) : null;

    // Obtener los datos del formulario
    $legalName = $_POST['legalName'] ?? '';
    $magicName = $_POST['magicName'] ?? '';
    $ownerId = $_POST['ownerId'] ?? 0;
    $status = $_POST['status'] ?? '';
    $companyTypeData = $_POST['companyTypeData'] ?? '';  // Recibir la cadena concatenada de tipos de empresa

    // Validaciones
    if (empty($companyTypeData)) {
        echo json_encode(['status' => 'error', 'error_code' => 'company_type_required', 'message' => 'Debe especificar un tipo de empresa.']);
        exit();
    }

    // Si se reciben tipos de empresa como una cadena separada por comas, separarlos
    $companyTypes = explode(',', $companyTypeData);

    // Eliminar los "0" en los tipos de empresa
    $companyTypes = array_filter($companyTypes, function($type) {
        return $type !== '0';
    });
    
    // Reindexar el array para evitar posibles problemas con índices no continuos
    $companyTypes = array_values($companyTypes);

    // Si el tipo de empresa es "custom", asegurarse de que se maneje correctamente
    if (in_array('custom', $companyTypes) && empty($companyTypes[1])) {
        echo json_encode(['status' => 'error', 'error_code' => 'custom_company_type_required', 'message' => 'Debe especificar un tipo de empresa personalizado.']);
        exit();
    }

    // Procesar el propietario y tipo de empresa
    if ($ownerId) {
        $ownerBusiness = new OwnerBusiness();
        $owner = $ownerBusiness->getTBOwner($ownerId);

        // Obtener el tipo de empresa
        $touristCompanyTypeBusiness = new TouristCompanyTypeBusiness();
        // Procesamos todos los tipos de empresa seleccionados
        $companyTypesObjects = [];
        foreach ($companyTypes as $companyTypeId) {
            $companyType = $touristCompanyTypeBusiness->getById($companyTypeId);  // Obtener tipo de empresa por ID
            if ($companyType) {
                $companyTypesObjects[] = $companyType;
            }
        }

        // Validar si se han obtenido todos los tipos de empresa correctamente
        if ($owner && count($companyTypesObjects) > 0) {
            $touristCompany = new TouristCompany(0, $legalName, $magicName, $ownerId, implode(',', $companyTypes), $photoUrls, $status);

            // Insertar los tipos de empresa seleccionados
            $touristCompany->setAllTouristCompanyType($companyTypesObjects);

            // Insertar la empresa en la base de datos
            $touristCompanyBusiness = new TouristCompanyBusiness();
            $result = $touristCompanyBusiness->insert($touristCompany);

            // Si el tipo de empresa es "custom", realizar acción adicional
            if (in_array('custom', $companyTypes)) {
                $customCompanyType = $companyTypes[1];  // El segundo valor es el tipo personalizado
                $touristCompany->setTbtouristcompanycustomcompanyType($customCompanyType);
                $touristCompanyBusiness->insertCustomizedtouristcompanytype($ownerId, $customCompanyType);
            }

            // Respuesta exitosa
            if ($result['status'] == 'success') {
                echo json_encode(['status' => 'success', 'message' => 'Empresa creada con éxito.'], JSON_UNESCAPED_UNICODE);
                exit;
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



// Lógica de actualización
if (isset($_POST['action']) && $_POST['action'] === 'update') {
    // Obtener los datos del formulario
    if (isset($_POST['companyId'], $_POST['ownerId'], $_POST['status'], $_POST['companyTypeData'])) {
        $id = $_POST['companyId'];
        $legalName = $_POST['legalName'];
        $magicName = $_POST['magicName'];
        $ownerId = $_POST['ownerId'];
        $companyTypeId = $_POST['companyTypeData'] ?? ''; // Obtener los tipos de empresa (separados por comas)
        $status = $_POST['status'];

        // Lógica para la foto (igual que antes)
        $photoFileName = '';
        $touristCompanyBusiness = new TouristCompanyBusiness();
        $currentTouristCompany = $touristCompanyBusiness->getById($id);
        $existingPhotoFileName = $currentTouristCompany->getTbtouristcompanyurl();

        // Subir nueva imagen si se selecciona
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

        // Procesar los tipos de empresa (cadena separada por comas)
        $companyTypes = explode(',', $companyTypeId); // Convertir la cadena a un array

        // Si el tipo de empresa es "custom", verificar si se especificó un nombre
        if (in_array('custom', $companyTypes) && empty($_POST['companyTypeData'])) {
            echo json_encode(['status' => 'error', 'error_code' => 'custom_company_type_required', 'message' => 'Debe especificar un tipo de empresa personalizado.']);
            exit();
        }

        // Actualizar la empresa
        if ($ownerId) {
            $touristCompany = new TouristCompany($id, $legalName, $magicName, $ownerId, implode(',', $companyTypes), $photoFileName, $status);
            $result = $touristCompanyBusiness->update($touristCompany);

            if ($result['status'] === 'success') {
                echo json_encode(['status' => 'success', 'message' => 'Empresa actualizada con éxito.']);
            } else {
                echo json_encode(['status' => 'error', 'error_code' => 'update_failed', 'message' => 'Error al actualizar la empresa.']);
            }
        } else {
            echo json_encode(['status' => 'error', 'error_code' => 'invalid_fields', 'message' => 'Campos inválidos en la actualización.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'error_code' => 'missing_fields', 'message' => 'Faltan campos obligatorios para la actualización.']);
    }
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
