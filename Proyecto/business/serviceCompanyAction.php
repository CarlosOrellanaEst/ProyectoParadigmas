<?php

include './serviceCompanyBusiness.php';

$response = array();
// para el AJAX de Create

if (isset($_POST['create'])) {
    $response = array();
    if (isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
        
        $uploadDir = '../images/services';
        $fileNames = array();
        $allowTypes = array('jpg', 'png', 'jpeg', 'gif');

        // Verifica que no se suban más de 5 archivos
        if (count($_FILES['images']['name']) > 5) {
            $response['status'] = 'error';
                    $response['message'] = 'Se permite maximo 5 imagenes';
                    echo json_encode($response);
            exit();
        }

        // Mover los archivos y obtener los nombres
        foreach ($_FILES['images']['name'] as $key => $fileName) {
            $targetFilePath = $uploadDir . '/' . basename($fileName);
            $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
        
            if (in_array($fileType, $allowTypes)) {
                $tempPath = $_FILES['images']['tmp_name'][$key];
                if (move_uploaded_file($tempPath, $targetFilePath)) {
                    $fileNames[] = basename($fileName);
                } else {
                    $response['status'] = 'error';
                    $response['message'] = 'Error al mover la imagen';
                    echo json_encode($response);
                    exit();
                }
            } else {
                $response['status'] = 'error';
                $response['message'] = 'Formato de imagen invalido';
                echo json_encode($response);
                exit();
            }
        }

        $photoUrls = implode(',', $fileNames);
        echo ($photoUrls);
     //   error_log('File names: ' . implode(',', $fileNames));
        $nameTBActivity = isset($_POST['nameTBActivity']) ? trim($_POST['nameTBActivity']) : '';
        $attributeTBActivityArray = isset($_POST['attributeTBActivityArray']) ? explode(',', $_POST['attributeTBActivityArray']) : [];
        $serviceName = $_POST['serviceName'] ?? '';

        if (!empty($serviceName)) {
            $service = new Service(0, $serviceName, $photoUrls, 1);
            $serviceBusiness = new ServiceBusiness();
            $result = $serviceBusiness->insertTBService($service);

            if ($result == 1) {
                $response = ['status' => 'success', 'message' => 'Service successfully added.'];
            } elseif ($result === null) {
                $response = ['status' => 'error', 'message' => 'Service already exists.'];
            } else {
                $response = ['status' => 'error', 'message' => 'Database error.'];
            }
        } else {
            $response = ['status' => 'error', 'message' => 'Empty fields are not allowed.'];
        }
        echo json_encode($response);
        exit();
    }
}


/* if (isset($_POST['nameService'])) {
    $name = trim($_POST['nameService']);
    // si van fotos llamo al de insertar de fotos
    if (isset($_FILES['imagenes']) && !empty($_FILES['imagenes']['name'][0])) { 
        $uploadDir = '../images/services/';
        $fileNames = array();
        $allowTypes = array('jpg', 'png', 'jpeg', 'gif');

        // Verifica que no se suban más de 5 archivos
        if (count($_FILES['imagenes']['name']) > 5) {
            header("location: ../view/serviceView.php?error=tooManyFiles");
            exit();
        }

        foreach ($_FILES['imagenes']['name'] as $key => $fileName) {
            $targetFilePath = $uploadDir . basename($fileName);
            $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

            if (in_array($fileType, $allowTypes)) {
                // Mueve el archivo temporal a la ubicación final
                $tempPath = $_FILES['imagenes']['tmp_name'][$key];
                if (move_uploaded_file($tempPath, $targetFilePath)) {
                    $fileNames[] = basename($fileName);
                } else {
                    header("location: ../view/serviceView.php?error=moveFailed");
                    exit();
                }
            } else {
                header("location: ../view/serviceView.php?error=invalidFileType");
                exit();
            }
        }

        $photoUrls = implode(',', $fileNames);
        
        $service = new Service(0, $name,  $photoUrls, 1);
        $serviceBusiness = new ServiceBusiness();
        $result = $serviceBusiness->insertTBService($service);

        if ($result) {
            header("location: ../view/serviceView.php?success=inserted");
        } else {
            header("location: ../view/serviceView.php?error=insertFailed");
        }
        exit();
    }
    echo json_encode($response);
    exit();
}
 */
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
    if (isset($_POST['rollID'])) {
        $id = $_POST['rollID'];
        $rollBusiness = new RollBusiness();
        $result = $rollBusiness ->deleteTBRoll($id);

        if ($result == 1) {
            header("location: ../view/rollView.php?success=deleted");
        } else {
            header("location: ../view/rollView.php?error=dbError");
        }
    } else {
        header("location: ../view/rollView.php?error=emptyField");
    }
} else {
    header("location: ../view/rollView.php?error=error");
}

?>