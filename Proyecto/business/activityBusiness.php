<?php

include_once '../data/activityData.php'; // Ajusta la ruta según tu estructura

class ActivityBusiness {

    public $activityData;

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

    public function removeImageFromActivity($activityId, $newImageUrls){
        return $this->activityData->removeImageFromActivity($activityId, $newImageUrls);
    }

    public function isImageInUse($imageToDelete){
        return $this->activityData->isImageInUse($imageToDelete);
    }

    // Método para obtener actividades por día
    public function getActivitiesByDay($date) {
        // Validación de formato de fecha, para asegurar que es correcta
        if (!$this->isValidDate($date)) {
            return [];  // Retornar un array vacío si la fecha no es válida
        }
        return $this->activityData->getActivitiesByDay($date);
    }

    // Método para obtener actividades por semana
    public function getActivitiesByWeek($date) {
        // Validación de formato de fecha
        if (!$this->isValidDate($date)) {
            return [];  // Retornar un array vacío si la fecha no es válida
        }
        return $this->activityData->getActivitiesByWeek($date);
    }

    // Método para obtener actividades por mes
    public function getActivitiesByMonth($date) {
        // Validación de formato de fecha
        if (!$this->isValidDate($date)) {
            return [];  // Retornar un array vacío si la fecha no es válida
        }
        return $this->activityData->getActivitiesByMonth($date);
    }

    // Validar si la fecha tiene el formato correcto (YYYY-MM-DD)
    private function isValidDate($date) {
        $d = DateTime::createFromFormat('Y-m-d', $date);
        return $d && $d->format('Y-m-d') === $date;
    }
}
