<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "Inicio del archivo touristCompanyAction.php";
include_once '../business/touristCompanyBusiness.php';
include_once '../domain/TouristCompany.php';
include_once '../domain/Owner.php';
include_once '../domain/TouristCompanyType.php';
include_once '../business/ownerBusiness.php';
include_once '../business/touristCompanyTypeBusiness.php';
include_once '../business/photoBusiness.php';
header('Content-Type: application/json');
$response = array();

if (isset($_POST['create'])) {
    /* if (isset($_FILES['imagenes']) && !empty($_FILES['imagenes']['name'][0])) { */
        $uploadDir = '../images/';
        $fileNames = array();
        $allowTypes = array('jpg', 'png', 'jpeg', 'gif');

        if (isset($_FILES['imagenes']) && empty($_FILES['imagenes']['name'][0])) {
            if (count($_FILES['imagenes']['name']) > 5) {
                $response['status'] = 'error';
                $response['message'] = 'Solo se permite subir un máximo de 5 imágenes';
                echo json_encode($response);
                exit();
            }
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
        $selectedCompanyTypes;
        if (isset($_POST['selectedCompanyTypes'])) {
            $selectedCompanyTypes = $_POST['selectedCompanyTypes']; // Esto será un array con los tipos de empresa seleccionados
        }

        $photoUrls = implode(',', $fileNames);

        $legalName = $_POST['legalName'] ?? '';
        $magicName = $_POST['magicName'] ?? '';
        $ownerId = $_POST['ownerId'] ?? 0;
        $companyTypeId = $_POST['companyType'] ?? 0;
        $status = $_POST['status'] ?? '';
        $customCompanyType = '';

        if ($companyTypeId === '0') {
            $customCompanyType = $_POST['customCompanyType'] ?? ''; // Verificamos que se envíe el valor personalizado

            if (empty($customCompanyType)) {
                // Manejar el error si no se ha proporcionado el valor del tipo personalizado
                echo json_encode(['status' => 'error', 'message' => 'Debe especificar un tipo de empresa personalizado.']);
                exit;
            }
        }

        if ($ownerId) {
            $ownerBusiness = new OwnerBusiness();
            $owner = $ownerBusiness->getTBOwner($ownerId);

            $touristCompanyTypeBusiness = new TouristCompanyTypeBusiness();
            $companyType = $touristCompanyTypeBusiness->getById($companyTypeId);

            if ($owner && $companyType) {
                $touristCompany = new TouristCompany(0, $legalName, $magicName, $ownerId, $companyTypeId, $photoUrls, $status);
                $touristCompany -> setAllTouristCompanyType($selectedCompanyTypes);
                
                if ($companyTypeId === '0') {
                    $touristCompany->setTbtouristcompanycustomcompanyType($customCompanyType);
                }

                $touristCompanyBusiness = new TouristCompanyBusiness();
                $result = $touristCompanyBusiness->insert($touristCompany);

                if ($result['status'] == 'success') {
                    $response = ['status' => 'success', 'message' => 'Empresa creada con éxito.'];
                } elseif ($result['status'] == 'error' && isset($result['message']) && $result['message'] === 'Empresa ya existe.') {
                    $response = ['status' => 'error', 'message' => 'La empresa ya existe.'];
                } else {
                    $response = ['status' => 'error', 'message' => 'Error en la base de datos: ' . $result['message']];
                }
            } else {
                $response = ['status' => 'error', 'message' => 'Propietario o tipo de compañía inválido.'];
            }
        } else {
            $response = ['status' => 'error', 'message' => 'El campo propietario es obligatorio.'];
        }

        echo json_encode($response);
        exit();
    /* }  else {
        $response = ['status' => 'error', 'message' => 'No se ha subido ninguna imagen.'];
        echo json_encode($response);
        exit();
    } */
}


if (isset($_POST['update'])) {
    if (isset($_POST['id'], $_POST['ownerId'], $_POST['status'])) {

        $id = $_POST['id'];
        $legalName = $_POST['legalName'];
        $magicName = $_POST['magicName'];
        $ownerId = $_POST['ownerId'];
        $companyTypeId = $_POST['companyType'];
        $status = $_POST['status'];

        $photoFileName = '';
        $touristCompanyBusiness = new TouristCompanyBusiness();
        $currentTouristCompany = $touristCompanyBusiness->getById($id);
        $existingPhotoFileName = $currentTouristCompany->getTbtouristcompanyurl();


        if (isset($_FILES['newImage']) && $_FILES['newImage']['error'] == UPLOAD_ERR_OK) {
            $uploadDir = '../images/';
            $fileName = basename($_FILES['newImage']['name']);
            $targetFilePath = $uploadDir . $fileName;
            $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
            $allowTypes = array('jpg', 'png', 'jpeg', 'gif');

            if (in_array($fileType, $allowTypes)) {
                if (move_uploaded_file($_FILES['newImage']['tmp_name'], $targetFilePath)) {
                    $photoFileName = $fileName;
                } else {
                    header("location: ../view/touristCompanyView.php?error=uploadFailed");
                    exit();
                }
            } else {
                header("location: ../view/touristCompanyView.php?error=invalidFileType");
                exit();
            }
        } else {
            $photoFileName = $existingPhotoFileName;
        }

        if ($ownerId) {

            $touristCompany = new TouristCompany($id, $legalName, $magicName, $ownerId, $companyTypeId, $photoFileName, $status);


            $result = $touristCompanyBusiness->update($touristCompany);


            if ($result['status'] === 'success') {
                header("location: ../view/touristCompanyView.php?success=updated");
                exit();
            } elseif ($result['status'] === 'error' && strpos($result['message'], 'Ya existe una compañía turística') !== false) {

                header("location: ../view/touristCompanyView.php?error=companyExists");
                exit();
            } else {
                header("location: ../view/touristCompanyView.php?error=updateFailed");
                exit();
            }

        } else {
            header("location: ../view/touristCompanyView.php?error=invalidFields");
            exit();
        }
    } else {
        header("location: ../view/touristCompanyView.php?error=missingFields");
        exit();
    }
}

if (isset($_POST['delete'])) {
    if (isset($_POST['id']) && is_numeric($_POST['id'])) {
        $id = $_POST['id'];
        $touristCompanyBusiness = new TouristCompanyBusiness();
        $result = $touristCompanyBusiness->delete($id);

        if ($result) {
            header("location: ../view/touristCompanyView.php?success=deleted");
            exit();
        } else {
            header("location: ../view/touristCompanyView.php?error=deleteFailed");
            exit();
        }
    } else {
        header("location: ../view/touristCompanyView.php?error=invalidId");
        exit();
    }
}

if (isset($_POST['deleteImage'])) {
    $companyId = $_POST['photoID'];
    $imageIndexToDelete = (int) $_POST['imageIndex'];

    $touristCompanyBusiness = new TouristCompanyBusiness();
    $currentTouristCompany = $touristCompanyBusiness->getById($companyId);

    $images = $currentTouristCompany->getTbtouristcompanyurl();


    if (isset($images[$imageIndexToDelete])) {

        $filePath = '../images/' . trim($images[$imageIndexToDelete]);


        $imageToDelete = trim($images[$imageIndexToDelete]);


        unset($images[$imageIndexToDelete]);


        $newImageUrls = implode(',', $images);
        $touristCompanyBusiness->removeImageFromCompany($companyId, $newImageUrls);


        $imageInUse = $touristCompanyBusiness->isImageInUse($imageToDelete);


        if (!$imageInUse && file_exists($filePath)) {
            unlink($filePath);
        }


        header("location: ../view/touristCompanyView.php?success=imagen_eliminada");
        exit();
    } else {

        header("location: ../view/touristCompanyView.php?error=image_not_found");
        exit();
    }
}