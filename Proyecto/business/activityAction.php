<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once '../business/activityBusiness.php'; // Ajusta la ruta según tu estructura
include_once '../domain/activity.php'; // Ajusta la ruta según tu estructura

if (isset($_POST['create'])) {
    $nameTBActivity = $_POST['nameTBActivity'];

    // Decodificar los arrays JSON recibidos
    $attributeTBActivityArray = isset($_POST['attributeTBActivityArray']) ? json_decode($_POST['attributeTBActivityArray'], true) : [];
    $dataAttributeTBActivityArray = isset($_POST['dataAttributeTBActivityArray']) ? json_decode($_POST['dataAttributeTBActivityArray'], true) : [];

    $statusTBActivity = isset($_POST['statusTBActivity']) ? 1 : 0;

    // Crear una instancia de Activity con los datos decodificados
    $activity = new Activity(0, $nameTBActivity, $attributeTBActivityArray, $dataAttributeTBActivityArray, $statusTBActivity);

    $activityBusiness = new ActivityBusiness();
    $result = $activityBusiness->insertActivity($activity);

    if ($result) {
        echo "Activity inserted successfully";
    } else {
        error_log("Error inserting activity: " . mysqli_error($conn));
        echo "Error inserting activity";
    }
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
