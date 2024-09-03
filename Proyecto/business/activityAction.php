<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once '../business/activityBusiness.php'; // Ajusta la ruta según tu estructura
include_once '../domain/activity.php'; // Ajusta la ruta según tu estructura

if (isset($_POST['insert'])) {
    $nameTBActivity = $_POST['nameTBActivity'];
    $attributeTBActivityArray = json_decode($_POST['attributeTBActivityArray'], true);
    $dataAttributeTBActivityArray = json_decode($_POST['dataAttributeTBActivityArray'], true);

    $statusTBActivity = isset($_POST['statusTBActivity']) ? 1 : 0;

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
    $attributeTBActivityArray = json_decode($_POST['attributeTBActivityArray'], true);
    $dataAttributeTBActivityArray = json_decode($_POST['dataAttributeTBActivityArray'], true);
    $statusTBActivity = isset($_POST['statusTBActivity']) ? 1 : 0;

    $activity = new Activity($idTBActivity, $nameTBActivity, $attributeTBActivityArray, $dataAttributeTBActivityArray, $statusTBActivity);

    $activityBusiness = new ActivityBusiness();
    $result = $activityBusiness->updateActivity($activity);

    if ($result) {
        echo "Activity updated successfully";
    } else {
        echo "Error updating activity";
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
