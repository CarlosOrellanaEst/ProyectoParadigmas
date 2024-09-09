<?php
include './serviceCompanyBusiness.php';

if (isset($_POST['create'])) {
    $response = array();

    // Verificación de las imágenes subidas
    if (isset($_FILES['imagenes']) && !empty($_FILES['imagenes']['name'][0])) {
        $uploadDir = '../images/services/';
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

        // Concatenar las URLs de imágenes
        $photoUrls = implode(',', $fileNames);

        // Validación y obtención de datos
        $companyID = isset($_POST['companyID']) ? trim($_POST['companyID']) : 0;
        $serviceIds = isset($_POST['serviceId']) ? $_POST['serviceId'] : array();

        // Concatenar IDs de servicios en una cadena separada por comas
        $serviceIdsString = implode(',', $serviceIds);

        if (!empty($companyID) && !empty($serviceIdsString)) {
            // Crear el objeto ServiceCompany con los datos necesarios
            $service = new ServiceCompany(0, $companyID, $serviceIdsString, $photoUrls, 1);
            $serviceBusiness = new serviceCompanyBusiness();
            $result = $serviceBusiness->insertTBServiceCompany($service);

            if ($result == 1) {
                $response = ['status' => 'success', 'message' => 'Servicio agregado correctamente.'];
            } else {
                $response = ['status' => 'error', 'message' => 'Error en la base de datos.'];
            }
        } else {
            $response = ['status' => 'error', 'message' => 'No se permiten campos vacíos.'];
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
    if (isset($_POST['rollName']) && isset($_POST['rollDescription']) && isset($_POST['rollID'])) {
        $name = $_POST['rollName'];
        $description = $_POST['rollDescription'];
        $id = $_POST['rollID'];

        if (strlen($name) > 0) {
            if (!is_numeric($name) && !is_numeric($description) && is_numeric($id)) {
                $roll = new Roll($id, $name, $description);
                $rollBusiness = new RollBusiness();
                $result = $rollBusiness->updateTBRoll($roll);

                if ($result == 1) {
                    header("location: ../view/rollView.php?success=updated");
                    exit();
                } else if ($result == null) {
                    header("location: ../view/rollView.php?error=alreadyExists");
                    exit();
                 } else {
                    header("location: ../view/rollView.php?error=dbError");
                    exit();
                }
            } else {
                header("location: ../view/rollView.php?error=numberFormat");
                exit();
            }
        } else {
            header("location: ../view/rollView.php?error=emptyField");
            exit();
        }
    } else {
        header("location: ../view/rollView.php?error=error");
        exit();
    }
}

if (isset($_POST['delete'])) { 
    if (isset($_POST['serviceID'])) {
        $id = $_POST['serviceID'];
        $serviceCompanyBusiness = new serviceCompanyBusiness();
        $result = $serviceCompanyBusiness ->deleteTBServiceCompany($id);

        if ($result == 1) {
            header("location: ../view/serviceView.php?success=deleted");
        } else {
            header("location: ../view/serviceView.php?error=dbError");
        }
    } else {
        header("location: ../view/serviceView.php?error=emptyField");
    }
} else {
    header("location: ../view/serviceView.php?error=error");
}



if (isset($_POST['deleteImage'])) {
    $serviceID = $_POST['serviceID'];
    $imageIndexToDelete = (int)$_POST['imageIndex']; // Asegúrate de que el índice sea un entero
    
    // Obtener las imágenes actuales del registro de la empresa
    $serviceCompanyBusiness = new ServiceCompanyBusiness();
    $currentServiceCompany = $serviceCompanyBusiness->getServiceCompany($serviceID);
    
    // Obtener la lista de imágenes actuales
    $images = explode(',', $currentServiceCompany->getTbservicecompanyURL());
    
    // Verificar si el índice de la imagen a eliminar es válido
    if (isset($images[$imageIndexToDelete])) {
        // Ruta completa del archivo en el servidor
        $filePath = '../images/services/' . trim($images[$imageIndexToDelete]);
        
        // Eliminar la imagen del servidor
        if (file_exists($filePath)) {
            unlink($filePath); // Eliminar la imagen físicamente del servidor
        }
        
        // Eliminar la imagen del array de URLs
        unset($images[$imageIndexToDelete]);
        
        // Reindexar el array de imágenes para evitar índices faltantes
        $images = array_values($images);
        
        // Actualizar la lista de imágenes en la base de datos
        $newImageUrls = implode(',', $images); // Convertir el array de nuevo en string separado por comas
        $serviceCompanyBusiness->removeImageFromServiceCompany($serviceID, $newImageUrls);
        
        // Redireccionar o mostrar un mensaje de éxito
        header("Location: ../view/touristCompanyView.php?success=image_deleted");
        exit();
    } else {
        // Redireccionar o mostrar un mensaje de error si el índice es inválido
        header("Location: ../view/touristCompanyView.php?error=image_not_found");
        exit();
    }

}
