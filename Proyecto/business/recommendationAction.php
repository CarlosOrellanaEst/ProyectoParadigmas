<?php
require_once '../business/activityBusiness.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $attribute = $_POST['attribute'] ?? '';
    $value = $_POST['value'] ?? '';

    if ($attribute && $value) {
        $activityBusiness = new ActivityBusiness();
        $activities = $activityBusiness->getAllActivitiesRecommended($attribute, $value);
        
        echo json_encode($activities);
    } else {
        echo json_encode(['error' => 'Missing parameters']);
    }
} else {
    echo json_encode(['error' => 'Invalid request method']);
}