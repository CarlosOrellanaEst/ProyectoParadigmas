<?php
include './serviceCompanyBusiness.php';

if (isset($_POST['create'])) {
    $response = array();

    // Verificación de las imágenes subidas
    $uploadDir = '../images/services/';
    $fileNames = array(); // Array para almacenar nombres de archivos procesados
    $allowTypes = array('jpg', 'png', 'jpeg', 'gif');

    // Verificar si se han subido más de 5 imágenes
    if (isset($_FILES['imagenes']) && !empty($_FILES['imagenes']['name'][0])) {
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
    }

    // Concatenar las URLs de imágenes
    $photoUrls = implode(',', $fileNames);

    // Validación y obtención de datos
    $companyID = isset($_POST['companyID']) ? trim($_POST['companyID']) : 0;
    $serviceIds = isset($_POST['serviceId']) ? $_POST['serviceId'] : array();

    // Concatenar IDs de servicios en una cadena separada por comas
    $serviceIdsString = explode(',', $serviceIds);

    // Validar que companyID no esté vacío
    if (!empty($companyID) && !empty($serviceIdsString)) {
        $serviceBusiness = new serviceCompanyBusiness();
        
        // Verificar si el companyID ya está en uso
        $existingServices = $serviceBusiness->companyWithServices($companyID);

        if (!empty($existingServices)) {
            $response = ['status' => 'error', 'message' => 'Ya existen servicios activos asociados a esta empresa.'];
            echo json_encode($response);
            exit();
        }

        // Crear el objeto ServiceCompany con los datos necesarios
        $service = new ServiceCompany(0, $companyID, $serviceIdsString, $photoUrls, 1);
        $result = $serviceBusiness->insertTBServiceCompany($service);

        if ($result['status'] === 'success') {
            $response = ['status' => 'success', 'message' => 'Servicio agregado correctamente.'];
        } else {
            $response = ['status' => 'error', 'message' => 'Error en la base de datos.'];            
        }
    } else {
        $response = ['status' => 'error', 'message' => 'No se permiten campos vacíos.'];
    }

    echo json_encode($response);
    exit();
}


if (isset($_POST['update'])) {
    $companyId = $_POST['companyId'];
    $serviceIds = isset($_POST['serviceId']) ? $_POST['serviceId'] : [];
    $serviceCompanyId = $_POST['id'];

    // Validación básica
    if (is_numeric($companyId) && is_numeric($serviceCompanyId)) {
        $serviceCompanyBusiness = new ServiceCompanyBusiness();
        $currentService = $serviceCompanyBusiness->getServiceCompany($serviceCompanyId);

        if ($currentService) {
            // Procesar imágenes subidas
            $uploadedImages = [];
            if (isset($_FILES['imagenes']) && count($_FILES['imagenes']['name']) > 0) {
                $totalFiles = count($_FILES['imagenes']['name']);
                for ($i = 0; $i < $totalFiles; $i++) {
                    $fileName = $_FILES['imagenes']['name'][$i];
                    $tempName = $_FILES['imagenes']['tmp_name'][$i];
                    $targetPath = "../images/services/" . basename($fileName);

                    if (move_uploaded_file($tempName, $targetPath)) {
                        $uploadedImages[] = $fileName;
                    }
                }
            }

            // Unir las imágenes existentes con las nuevas
            $existingImages = explode(',', $currentService->getTbservicecompanyURL());
            $newImageUrls = array_merge($existingImages, $uploadedImages);
            $imageUrlsString = implode(',', $newImageUrls);

            // Actualizar el registro
            $service = new ServiceCompany($serviceCompanyId, $companyId, implode(',', $serviceIds), $imageUrlsString, 1);
            $result = $serviceCompanyBusiness->updateTBServiceCompany($service);

            if ($result['status'] === 'success') {
                header("Location: ../view/serviceView.php?success=updated");
                exit();
            } elseif ($result['status'] === 'error' && $result['message'] === 'Service already exists') {
                header("Location: ../view/serviceView.php?error=alreadyExists");
                exit();
            } else {
                header("Location: ../view/serviceView.php?error=dbError");
                exit();
            }
        } else {
            header("Location: ../view/serviceView.php?error=notFound");
            exit();
        }
    } else {
        header("Location: ../view/serviceView.php?error=invalidInput");
        exit();
    }
}





if (isset($_POST['delete'])) { 
    if (isset($_POST['id'])) { // Aquí también aseguramos que use 'serviceID'
        $id = $_POST['id'];
        $serviceCompanyBusiness = new ServiceCompanyBusiness();
        $result = $serviceCompanyBusiness->deleteTBServiceCompany($id);

        if ($result == 1) {
            header("location: ../view/serviceView.php?success=deleted");
        } else {
            header("location: ../view/serviceView.php?error=dbError");
        }
    } else {
        header("location: ../view/serviceView.php?error=emptyField");
    }
}



if (isset($_POST['deleteImage'])) {
    $serviceID = $_POST['id'];
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
        header("Location: ../view/serviceView.php?success=image_deleted");
        exit();
    } else {
        // Redireccionar o mostrar un mensaje de error si el índice es inválido
        header("Location: ../view/serviceView.php?error=image_not_found");
        exit();
    }
    
}


if (isset($_POST['deleteService'])) {
    $serviceID = $_POST['id']; // ID del servicio a eliminar
    $serviceIndexToDelete = (int)$_POST['serviceIndex']; // Índice del servicio a eliminar
    
    // Obtener los servicios actuales del registro
    $serviceCompanyBusiness = new ServiceCompanyBusiness();
    $currentServiceCompany = $serviceCompanyBusiness->getServiceCompany($serviceID);
    
    // Obtener la lista de servicios actuales
    $services = explode(',', $currentServiceCompany->getTbserviceid());
    
    // Verificar si el índice del servicio a eliminar es válido
    if (isset($services[$serviceIndexToDelete])) {
        // Eliminar el servicio del array de servicios
        unset($services[$serviceIndexToDelete]);
        
        // Reindexar el array de servicios para evitar índices faltantes
        $services = array_values($services);
        
        // Actualizar la lista de servicios en la base de datos
        $newServiceIds = implode(',', $services); // Convertir el array de nuevo en string separado por comas
        $serviceCompanyBusiness->removeServiceFromServiceCompany($serviceID, $newServiceIds);
        
        // Redireccionar o mostrar un mensaje de éxito
        header("Location: ../view/serviceView.php?success=service_deleted");
        exit();
    } else {
        // Redireccionar o mostrar un mensaje de error si el índice es inválido
        header("Location: ../view/serviceView.php?error=service_not_found");
        exit();
    }
}
