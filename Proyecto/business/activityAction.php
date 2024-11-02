<?php
include_once '../business/activityBusiness.php'; 
include_once '../domain/Activity.php'; 
header('Content-Type: application/json');

$response = array(); 

// Crear una nueva actividad
if (isset($_POST['create'])) {
    
    $photoUrls = ''; 
    $activityDate = $_POST['activityDate'];
    $fechaActual = date('Y-m-d');

    if (empty($activityDate)) {
        echo json_encode(['status' => 'error', 'error_code' => 'invalid_date', 'message' => 'La fecha de la actividad no puede estar vacía.']);
        exit();
    } elseif ($activityDate < $fechaActual) {
        echo json_encode(['status' => 'error', 'error_code' => 'past_date', 'message' => 'No se puede registrar una actividad con una fecha anterior a la actual.']);
        exit();
    }

    if (isset($_FILES['imagenes']) && !empty($_FILES['imagenes']['name'][0])) {
        $uploadDir = '../images/activity/';
        $fileNames = array(); 
        $allowTypes = array('jpg', 'png', 'jpeg', 'gif');

        

        // Validación de límite de imágenes (máximo 5)
        if (count($_FILES['imagenes']['name']) > 5) {
            echo json_encode(['status' => 'error', 'error_code' => 'image_limit', 'message' => 'Solo se permite subir un máximo de 5 imágenes.']);
            exit();
        }

        foreach ($_FILES['imagenes']['name'] as $key => $fileName) {
            $targetFilePath = $uploadDir . basename($fileName);
            $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

            if (in_array($fileType, $allowTypes)) {
                if (move_uploaded_file($_FILES['imagenes']['tmp_name'][$key], $targetFilePath)) {
                    $fileNames[] = basename($fileName);
                } else {
                    echo json_encode(['status' => 'error', 'error_code' => 'image_upload_error', 'message' => 'Error al mover la imagen al directorio.']);
                    exit();
                }
            } else {
                echo json_encode(['status' => 'error', 'error_code' => 'invalid_image_format', 'message' => 'Formato de imagen inválido. Solo se permiten JPG, PNG, JPEG, y GIF.']);
                exit();
            }
        }

        $photoUrls = implode(',', $fileNames);
    }

    $nameTBActivity = isset($_POST['nameTBActivity']) ? trim($_POST['nameTBActivity']) : '';
    $serviceID = isset($_POST['serviceId']) ? trim($_POST['serviceId']) : 0;
    $attributeTBActivityArray = isset($_POST['attributeTBActivityArray']) ? $_POST['attributeTBActivityArray'] : '';
    $dataAttributeTBActivityArray = isset($_POST['dataAttributeTBActivityArray']) ? $_POST['dataAttributeTBActivityArray'] : '';
    $latitude = isset($_POST['latitude']) ? floatval($_POST['latitude']) : null;
    $longitude = isset($_POST['longitude']) ? floatval($_POST['longitude']) : null;

    if (empty($nameTBActivity)) {
        echo json_encode(['status' => 'error', 'error_code' => 'missing_name', 'message' => 'El nombre de la actividad es requerido.']);
        exit();
    }
    if (empty($serviceID)) {
        echo json_encode(['status' => 'error', 'error_code' => 'missing_service_id', 'message' => 'El ID del servicio es requerido.']);
        exit();
    }
    if (empty($latitude) || empty($longitude)) {
        echo json_encode(['status' => 'error', 'error_code' => 'missing_coordinates', 'message' => 'Las coordenadas de latitud y longitud son requeridas.']);
        exit();
    }

    $activity = new Activity(0, $nameTBActivity, $serviceID, $attributeTBActivityArray, $dataAttributeTBActivityArray, $photoUrls, 1, $activityDate, $latitude, $longitude);
    $activityBusiness = new ActivityBusiness();
    $result = $activityBusiness->insertActivity($activity);

    if ($result['status'] == 'error' && isset($result['message']) && $result['message'] == 'Ya existe una actividad con el mismo nombre y está activa.') {
        echo json_encode(['status' => 'error', 'message' => 'Ya existe una actividad con el mismo nombre y está activa.']);
        exit();
    }

    if ($result) {
        echo json_encode(['status' => 'success', 'message' => 'Actividad insertada correctamente.']);
    } else {
        echo json_encode(['status' => 'error', 'error_code' => 'insert_error', 'message' => 'Error al insertar actividad.']);
    }
    exit();
}

// Actualizar una actividad existente
if (isset($_POST['update'])) {
    $idTBActivity = $_POST['idTBActivity'];
    $nameTBActivity = $_POST['nameTBActivity'];
    $attributeTBActivityArray = isset($_POST['attributeTBActivityArrayTable']) ? $_POST['attributeTBActivityArrayTable'] : [];
    $dataAttributeTBActivityArray = isset($_POST['dataAttributeTBActivityArrayTable']) ? $_POST['dataAttributeTBActivityArrayTable'] : [];
    $statusTBActivity = isset($_POST['statusTBActivity']) ? 1 : 0;
    $serviceId = $_POST['serviceId'];
    $latitude = isset($_POST['latitude']) ? floatval($_POST['latitude']) : null;
    $longitude = isset($_POST['longitude']) ? floatval($_POST['longitude']) : null;

    if (empty($latitude) || empty($longitude)) {
        echo json_encode(['status' => 'error', 'error_code' => 'missing_coordinates', 'message' => 'Las coordenadas de latitud y longitud son requeridas.']);
        exit();
    }

    $activityDate = $_POST['activityDate'];
    $fechaActual = date('Y-m-d');

    if (empty($activityDate)) {
        echo json_encode(['status' => 'error', 'error_code' => 'invalid_date', 'message' => 'La fecha de la actividad no puede estar vacía.']);
        exit();
    } elseif ($activityDate < $fechaActual) {
        echo json_encode(['status' => 'error', 'error_code' => 'past_date', 'message' => 'No se puede registrar una actividad con una fecha anterior a la actual.']);
        exit();
    }

    $existingImages = $_POST['existingImages'] ?? '';
    $uploadedImages = [];

    if (is_string($existingImages)) {
        $existingImages = explode(',', $existingImages);
    }

    if (isset($_FILES['imagenes']) && count($_FILES['imagenes']['name']) > 0) {
        for ($i = 0; $i < count($_FILES['imagenes']['name']); $i++) {
            $imageName = $_FILES['imagenes']['name'][$i];
            $imageTmp = $_FILES['imagenes']['tmp_name'][$i];
            if ($imageTmp != "") {
                $destination = '../images/activity/' . $imageName;
                move_uploaded_file($imageTmp, $destination);
                $uploadedImages[] = $imageName;
            }
        }
    }

    $allImages = array_merge($existingImages, $uploadedImages);
    $allImages = implode(',', array_filter($allImages));

    $activityBusiness = new ActivityBusiness();
    $activity = new Activity($idTBActivity, $nameTBActivity, $serviceId, $attributeTBActivityArray, $dataAttributeTBActivityArray, $allImages, 1, $activityDate, $latitude, $longitude);
    $result = $activityBusiness->updateActivity($activity);

    if ($result['status'] === 'success') {
        echo json_encode(['status' => 'success', 'message' => 'Actividad actualizada correctamente.']);
    } elseif ($result['status'] === 'error' && $result['message'] === 'La actividad ya existe con el mismo nombre') {
        echo json_encode(['status' => 'error', 'error_code' => 'duplicate_name', 'message' => $result['message']]);
    } else {
        echo json_encode(['status' => 'error', 'error_code' => 'update_error', 'message' => $result['message'] ?? 'Error al actualizar actividad.']);
    }
    exit();
}


// Eliminar una actividad
if (isset($_POST['delete'])) {
    $idTBActivity = $_POST['idTBActivity'];

    $activityBusiness = new ActivityBusiness();
    $result = $activityBusiness->deleteActivity($idTBActivity);

    if ($result) {
        echo json_encode(['status' => 'success', 'message' => 'Actividad eliminada correctamente.']);
    } else {
        echo json_encode(['status' => 'error', 'error_code' => 'delete_error', 'message' => 'Error al eliminar actividad.']);
    }
    exit();
}

// Eliminar una imagen de una actividad
if (isset($_POST['deleteImage'])) {
    $activityId = $_POST['idTBActivity']; 
    $imageIndexToDelete = (int)$_POST['imageIndex'];  

    $activityBusiness = new ActivityBusiness();
    $currentActivity = $activityBusiness->getActivityById($activityId);
    $images = $currentActivity->getTbactivityURL(); 

    if (is_string($images)) {
        $images = explode(',', $images);
    }

    if (isset($images[$imageIndexToDelete])) {
        $filePath = '../images/activity/' . trim($images[$imageIndexToDelete]);
        $imageToDelete = trim($images[$imageIndexToDelete]);
        unset($images[$imageIndexToDelete]);
        $newImageUrls = implode(',', $images);  
        $activityBusiness->removeImageFromActivity($activityId, $newImageUrls);
        
        $imageInUse = $activityBusiness->isImageInUse($imageToDelete);

        if (!$imageInUse && file_exists($filePath)) {
            unlink($filePath);  
        }

        echo json_encode(['status' => 'success', 'message' => 'Imagen eliminada correctamente.']);
    } else {
        echo json_encode(['status' => 'error', 'error_code' => 'image_not_found', 'message' => 'La imagen no fue encontrada.']);
    }
    exit();
}