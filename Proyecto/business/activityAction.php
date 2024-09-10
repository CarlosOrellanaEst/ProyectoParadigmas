<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once '../business/activityBusiness.php'; 
include_once '../domain/Activity.php'; 

if (isset($_POST['create'])) {
    $response = array();

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
            error_log("Error al insertar actividad");
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
    // Obtener datos del formulario
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
        header("Location: ../view/activityView.php?success=updated");
    } else {
        header("Location: ../view/activityView.php?error=updateFailed");
    }
}



if (isset($_POST['delete'])) {
    $idTBActivity = $_POST['idTBActivity'];

    $activityBusiness = new ActivityBusiness();
    $result = $activityBusiness->deleteActivity($idTBActivity);

    if ($result) {
        echo "Activity deleted successfully";
    } else {
        echo "Error deleting activity";
    }
}
