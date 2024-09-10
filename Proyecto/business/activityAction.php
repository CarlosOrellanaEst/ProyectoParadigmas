<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once '../business/activityBusiness.php'; 
include_once '../domain/Activity.php'; 

$response = array(); // Para almacenar la respuesta de cada acción

if (isset($_POST['create'])) {
    // Verificación de las imágenes subidas
    if (isset($_FILES['imagenes']) && !empty($_FILES['imagenes']['name'][0])) {
        $uploadDir = '../images/activity/';
        $fileNames = array(); // Array para almacenar nombres de archivos procesados
        $allowTypes = array('jpg', 'png', 'jpeg', 'gif');

        // Verificar si se han subido más de 5 imágenes
        if (count($_FILES['imagenes']['name']) > 5) {
            $response['status'] = 'error';
            $response['message'] = 'Solo se permite subir un máximo de 5 imágenes';
            echo json_encode($response);
            exit();
        }

        // Procesar y mover los archivos
        foreach ($_FILES['imagenes']['name'] as $key => $fileName) {
            $targetFilePath = $uploadDir . basename($fileName);
            $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

            if (in_array($fileType, $allowTypes)) {
                if (move_uploaded_file($_FILES['imagenes']['tmp_name'][$key], $targetFilePath)) {
                    $fileNames[] = basename($fileName);
                } else {
                    $response['status'] = 'error';
                    $response['message'] = 'Error al mover la imagen al directorio.';
                    echo json_encode($response);
                    exit();
                }
            } else {
                $response['status'] = 'error';
                $response['message'] = 'Formato de imagen inválido. Solo se permiten JPG, PNG, JPEG, y GIF.';
                echo json_encode($response);
                exit();
            }
        }

        // Concatenar las URLs de las imágenes
        $photoUrls = implode(',', $fileNames);

        // Validación y obtención de datos
        $nameTBActivity = isset($_POST['nameTBActivity']) ? trim($_POST['nameTBActivity']) : '';
        $serviceID = isset($_POST['serviceId']) ? trim($_POST['serviceId']) : 0;
        $attributeTBActivityArray = isset($_POST['attributeTBActivityArray']) ? explode(',', $_POST['attributeTBActivityArray']) : [];
        $dataAttributeTBActivityArray = isset($_POST['dataAttributeTBActivityArray']) ? explode(',', $_POST['dataAttributeTBActivityArray']) : [];

        // Validar campos obligatorios
        if (empty($nameTBActivity)) {
            echo json_encode(['status' => 'error', 'message' => 'El nombre de la actividad es requerido.']);
            exit();
        }

        if (empty($serviceID)) {
            echo json_encode(['status' => 'error', 'message' => 'El ID del servicio es requerido.']);
            exit();
        }

        // Crear una instancia de Activity con los datos
        $activity = new Activity(0, $nameTBActivity, $serviceID, $attributeTBActivityArray, $dataAttributeTBActivityArray, $photoUrls, 1);
        $activityBusiness = new ActivityBusiness();

        // Insertar la actividad en la base de datos
        $result = $activityBusiness->insertActivity($activity);

        // Respuesta según el resultado
        if ($result) {
            $response = ['status' => 'success', 'message' => 'Actividad insertada correctamente.'];
        } else {
            $response = ['status' => 'error', 'message' => 'Error al insertar actividad.'];
        }

        echo json_encode($response);
        exit();
    } else {
        // Error por falta de imágenes
        $response = ['status' => 'error', 'message' => 'No se han subido imágenes.'];
        echo json_encode($response);
        exit();
    }
} 


if (isset($_POST['update'])) {
    $idTBActivity = $_POST['idTBActivity'];
    $nameTBActivity = $_POST['nameTBActivity'];
    $attributeTBActivityArray = $_POST['attributeTBActivityArray'] ?? [];
    $dataAttributeTBActivityArray = $_POST['dataAttributeTBActivityArray'] ?? [];
    $statusTBActivity = isset($_POST['statusTBActivity']) ? 1 : 0;
    $serviceId = $_POST['serviceId'];

    // Manejar la carga de imágenes
    $existingImages = $_POST['existingImages'] ?? '';
    $uploadedImages = [];

    // Verificar si hay imágenes cargadas
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

    // Fusionar imágenes existentes con las nuevas
    $allImages = array_merge(explode(',', $existingImages), $uploadedImages);
    $allImages = implode(',', array_filter($allImages)); // Eliminar elementos vacíos

    // Actualizar la actividad en la base de datos
    $activityBusiness = new ActivityBusiness();
    $activity = new Activity($idTBActivity, $nameTBActivity, $serviceId, $attributeTBActivityArray, $dataAttributeTBActivityArray,  $allImages, 1);
   
    $result = $activityBusiness->updateActivity($activity);

    if ($result) {
        echo json_encode(['status' => 'success', 'message' => 'Actividad actualizada correctamente.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error al actualizar actividad.']);
    }
    exit();
}

if (isset($_POST['delete'])) {
    $idTBActivity = $_POST['idTBActivity'];

    $activityBusiness = new ActivityBusiness();
    $result = $activityBusiness->deleteActivity($idTBActivity);

    if ($result) {
        echo json_encode(['status' => 'success', 'message' => 'Actividad eliminada correctamente.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error al eliminar actividad.']);
    }
    exit();
}


if (isset($_POST['deleteImage'])) {
    $activityId = $_POST['idTBActivity'];  // ID de la actividad
    $imageIndexToDelete = (int)$_POST['imageIndex'];  // Índice de la imagen a eliminar

    $activityBusiness = new ActivityBusiness();
    $currentActivity = $activityBusiness->getActivityById($activityId);
    
    // Asumimos que getTbactivityURL() devuelve un array, así que no necesitas hacer explode
    $images = $currentActivity->getTbactivityURL();  // Obtener las URLs de las imágenes como un array

    // Verificar si el índice de la imagen a eliminar existe
    if (isset($images[$imageIndexToDelete])) {
        // Obtener la ruta completa de la imagen a eliminar
        $filePath = '../images/activity/' . trim($images[$imageIndexToDelete]);
        
        // Guardar la imagen que se eliminará
        $imageToDelete = trim($images[$imageIndexToDelete]);
        
        // Eliminar la imagen del array
        unset($images[$imageIndexToDelete]);
        
        // Actualizar las URLs de las imágenes en la base de datos
        $newImageUrls = implode(',', $images);  // Volver a convertir el array a cadena
        $activityBusiness->removeImageFromActivity($activityId, $newImageUrls);
        
        // Verificar si la imagen está en uso por otra actividad
        $imageInUse = $activityBusiness->isImageInUse($imageToDelete);
        
        // Si la imagen no está en uso y existe en el sistema de archivos, se elimina
        if (!$imageInUse && file_exists($filePath)) {
            unlink($filePath);  // Eliminar la imagen del servidor
        }

        echo json_encode(['status' => 'success', 'message' => 'Imagen eliminada correctamente.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'La imagen no fue encontrada.']);
    }
    exit();
}



