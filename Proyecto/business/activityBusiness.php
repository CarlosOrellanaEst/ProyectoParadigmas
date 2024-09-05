<?php

include_once '../data/activityData.php'; // Ajusta la ruta según tu estructura

class ActivityBusiness {

    private $activityData;

    public function __construct() {
        $this->activityData = new ActivityData();
    }

    public function insertActivity($activity) {
        return $this->activityData->insertActivity($activity);
    }

    public function updateActivity($activity) {
        return $this->activityData->updateActivity($activity);
    }

    public function deleteActivity($id) {
        return $this->activityData->deleteActivity($id);
    }

    public function getAllActivities() {
        return $this->activityData->getAllActivities();
    }

    public function getActivityById($id) {
        return $this->activityData->getActivityById($id);
    }

    public function getActivityByName($name) {
        return $this->activityData->getActivityByName($name);
    }
}
