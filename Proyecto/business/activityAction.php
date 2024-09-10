<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once '../business/activityBusiness.php'; 
include_once '../domain/Activity.php'; 

$response = array(); 

if (isset($_POST['create'])) {
    
    if (isset($_FILES['imagenes']) && !empty($_FILES['imagenes']['name'][0])) {
        $uploadDir = '../images/activity/';
        $fileNames = array(); 
        $allowTypes = array('jpg', 'png', 'jpeg', 'gif');

        
        if (count($_FILES['imagenes']['name']) > 5) {
            $response['status'] = 'error';
            $response['message'] = 'Solo se permite subir un máximo de 5 imágenes.';
            echo json_encode($response);
            exit();
        }

     
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

        
        $photoUrls = implode(',', $fileNames);

        
        $nameTBActivity = isset($_POST['nameTBActivity']) ? trim($_POST['nameTBActivity']) : '';
        $serviceID = isset($_POST['serviceId']) ? trim($_POST['serviceId']) : 0;
        $attributeTBActivityArray = isset($_POST['attributeTBActivityArray']) ? explode(',', $_POST['attributeTBActivityArray']) : [];
        $dataAttributeTBActivityArray = isset($_POST['dataAttributeTBActivityArray']) ? explode(',', $_POST['dataAttributeTBActivityArray']) : [];

       
        if (empty($nameTBActivity)) {
            echo json_encode(['status' => 'error', 'message' => 'El nombre de la actividad es requerido.']);
            exit();
        }

        if (empty($serviceID)) {
            echo json_encode(['status' => 'error', 'message' => 'El ID del servicio es requerido.']);
            exit();
        }

    
        $activity = new Activity(0, $nameTBActivity, $serviceID, $attributeTBActivityArray, $dataAttributeTBActivityArray, $photoUrls, 1);
        $activityBusiness = new ActivityBusiness();

       
        $result = $activityBusiness->insertActivity($activity);

        
        if (is_array($result) && $result['status'] == 'error') {
            echo json_encode($result); 
            exit();
        }

        if ($result) {
            $response = ['status' => 'success', 'message' => 'Actividad insertada correctamente.'];
        } else {
            $response = ['status' => 'error', 'message' => 'Error al insertar actividad.'];
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
    $idTBActivity = $_POST['idTBActivity'];
    $nameTBActivity = $_POST['nameTBActivity'];
    $attributeTBActivityArray = $_POST['attributeTBActivityArray'] ?? [];
    $dataAttributeTBActivityArray = $_POST['dataAttributeTBActivityArray'] ?? [];
    $statusTBActivity = isset($_POST['statusTBActivity']) ? 1 : 0;
    $serviceId = $_POST['serviceId'];

  
    $existingImages = $_POST['existingImages'] ?? '';
    $uploadedImages = [];

  
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

    $allImages = array_merge(explode(',', $existingImages), $uploadedImages);
    $allImages = implode(',', array_filter($allImages)); 


    $activityBusiness = new ActivityBusiness();
    $activity = new Activity($idTBActivity, $nameTBActivity, $serviceId, $attributeTBActivityArray, $dataAttributeTBActivityArray, $allImages, 1);
   
    $result = $activityBusiness->updateActivity($activity);

    if (is_array($result) && $result['status'] == 'error') {
        echo json_encode($result); 
        exit();
    }

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
    $activityId = $_POST['idTBActivity']; 
    $imageIndexToDelete = (int)$_POST['imageIndex'];  

    $activityBusiness = new ActivityBusiness();
    $currentActivity = $activityBusiness->getActivityById($activityId);
    
    $images = $currentActivity->getTbactivityURL(); 

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
        echo json_encode(['status' => 'error', 'message' => 'La imagen no fue encontrada.']);
    }
    exit();
}
