<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once '../business/activityBusiness.php'; 
include_once '../domain/activity.php'; 


if (isset($_POST['create'])) {
    $nameTBActivity = isset($_POST['nameTBActivity']) ? trim($_POST['nameTBActivity']) : '';
    $attributeTBActivityArray = isset($_POST['attributeTBActivityArray']) ? explode(',', $_POST['attributeTBActivityArray']) : [];
    $dataAttributeTBActivityArray = isset($_POST['dataAttributeTBActivityArray']) ? explode(',', $_POST['dataAttributeTBActivityArray']) : [];

    // Validar los datos
    if (empty($nameTBActivity)) {
        echo json_encode(['status' => 'error', 'message' => 'El nombre de la actividad es requerido.']);
        exit();
    }

    // Crear una instancia de Activity con los datos decodificados
    $activity = new Activity(0, $nameTBActivity, $attributeTBActivityArray, $dataAttributeTBActivityArray, 1);

    // Crear una instancia de ActivityBusiness
    $activityBusiness = new ActivityBusiness();

    // Insertar la actividad en la base de datos
    $result = $activityBusiness->insertActivity($activity);

    // Enviar respuesta al cliente
    if ($result) {
        echo json_encode(['status' => 'success', 'message' => 'Actividad insertada correctamente']);
    } else {
        error_log("Error al insertar actividad");
        echo json_encode(['status' => 'error', 'message' => 'Error al insertar actividad']);
    }
} else {
    // Manejo de otros casos si es necesario
    echo json_encode(['status' => 'error', 'message' => 'Solicitud no válida']);
}





if (isset($_POST['update'])) {
    $idTBActivity = $_POST['idTBActivity'];
    $nameTBActivity = $_POST['nameTBActivity'];
    $attributeTBActivityArray = $_POST['attributeTBActivityArray'];
    $dataAttributeTBActivityArray = $_POST['dataAttributeTBActivityArray'];
    $statusTBActivity = isset($_POST['statusTBActivity']) ? 1 : 0;

    // Procesar los atributos y datos
    $attributes = [];
    $dataAttributes = [];
    foreach ($attributeTBActivityArray as $index => $attribute) {
        if (!empty($attribute)) {
            $attributes[] = $attribute;
            $dataAttributes[] = isset($dataAttributeTBActivityArray[$index]) ? $dataAttributeTBActivityArray[$index] : '';
        }
    }

    // Crear objeto Activity
    $activity = new Activity($idTBActivity, $nameTBActivity, $attributes, $dataAttributes, $statusTBActivity);

    // Crear objeto ActivityBusiness
    $activityBusiness = new ActivityBusiness();
    $result = $activityBusiness->updateActivity($activity);

    if ($result) {
        header("Location: ../views/success.php?message=Activity updated successfully");
    } else {
        header("Location: ../views/error.php?message=Error updating activity");
    }
    exit();
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
