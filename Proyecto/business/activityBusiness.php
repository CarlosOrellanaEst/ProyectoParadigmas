<?php

include_once '../data/activityData.php';

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
    // obtenemos todos los atributos de las actividades, sin repetirse
    public function getAllActivitiesForRecomendations() {
        $activities = $this->activityData->getAllActivities();
        $uniqueAttributes = [];
    
        foreach ($activities as $activity) {
            foreach ($activity['tbactivityatributearray'] as $attributeData) {
                $cleanAttribute = trim($attributeData);
                
                // Convertimos a minúsculas para la comparación
                $lowercaseAttribute = strtolower($cleanAttribute);
                
                // Se verifica si es en minuscula o mayuscula
                // Verificamos si existe usando la versión en minúsculas
                $exists = false;
                foreach ($uniqueAttributes as $existingAttribute) {
                    if (strtolower($existingAttribute) === $lowercaseAttribute) {
                        $exists = true;
                        break;
                    }
                }
                
                // Si no existe, agregamos la versión original (no la versión en minúsculas)
                if (!$exists) {
                    $uniqueAttributes[] = $cleanAttribute;
                }
            }
        }
        
        sort($uniqueAttributes);
        return $uniqueAttributes;
    }
    
    // por atributo, obtenemos todos los valores únicos de las actividades
    public function getAllUniqueValuesForRecomendations($attribute) {
        // Obtenemos todos los valores para el atributo
        $values = $this->activityData->getAllValuesPerAttribute($attribute);
        $uniqueValues = [];
        
        // Filtramos los valores únicos
        foreach ($values as $value) {
            $lowercaseValue = strtolower($value);
            
            // Verificamos si ya existe el valor
            $exists = false;
            foreach ($uniqueValues as $existingValue) {
                if (strtolower($existingValue) === $lowercaseValue) {
                    $exists = true;
                    break;
                }
            }
            if (!$exists) {
                $uniqueValues[] = $value;
            }
        }
        
        sort($uniqueValues);
        return $uniqueValues;
    }
    
    // el metodo que llamamos en la vista
    public function getAllAttributesWithValues() {
        $uniqueAttributes = $this->getAllActivitiesForRecomendations();
        $result = [];
        
        foreach ($uniqueAttributes as $attribute) {
            $result[$attribute] = $this->getAllUniqueValuesForRecomendations($attribute);
        }
        
        return $result;
    }

    public function getAllActivitiesByOwner($ownerId) {
        return $this->activityData->getAllActivitiesByOwner($ownerId);
    }

    public function getAllActivitiesRecommended($attribute, $value) {
        return $this->activityData->getAllActivitiesRecommended($attribute, $value);
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
