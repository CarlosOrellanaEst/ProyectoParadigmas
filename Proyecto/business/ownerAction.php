<?php
include_once './ownerBusiness.php';

header('Content-Type: application/json');


if (isset($_POST['create'])) {
    $response = array();

    if (
        isset($_POST['ownerLegalIdentification']) &&
        isset($_POST['ownerEmail']) &&
        isset($_POST['password'])
    ) {
        $name = isset($_POST['ownerName']) ? trim($_POST['ownerName']) : '';
        $surnames = isset($_POST['ownerSurnames']) ? trim($_POST['ownerSurnames']) : '';
        $nickName = isset($_POST['nickName']) ? trim($_POST['nickName']) : '';
        $legalIdentification = trim($_POST['ownerLegalIdentification']);
        $phone = isset($_POST['ownerPhone']) ? trim($_POST['ownerPhone']) : '';
        $email = strtolower(trim($_POST['ownerEmail']));
        $direction = isset($_POST['ownerDirection']) ? trim($_POST['ownerDirection']) : '';
        $idType = trim($_POST['idType']);
        $password = trim($_POST['password']);
        $confirmPassword = isset($_POST['confirmPassword']) ? trim($_POST['confirmPassword']) : '';

        if($password !== $confirmPassword) {
            echo json_encode(['status' => 'error', 'error_code' => 'password_mismatch', 'message' => 'Las contraseñas no coinciden']);
            exit();
        }
     
        $encryptedPassword = password_hash($password, PASSWORD_BCRYPT);
        
        $fileUploaded = isset($_FILES['imagen']) && $_FILES['imagen']['error'] == UPLOAD_ERR_OK;
        $targetFilePath = '';

        if ($fileUploaded) {
            $uploadDir = '../images/';
            $fileName = basename($_FILES['imagen']['name']);
            $targetFilePath = $uploadDir . $fileName;

            $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
            $allowTypes = array('jpg', 'png', 'jpeg', 'gif');

            if (in_array($fileType, $allowTypes)) {
                if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $targetFilePath)) {
                    echo json_encode(['status' => 'error', 'error_code' => 'image_upload_failed', 'message' => 'Fallo al subir la imagen']);
                    exit();
                }
            } else {
                echo json_encode(['status' => 'error', 'error_code' => 'invalid_file_type', 'message' => 'Tipo de archivo no permitido']);
                exit();
            }
        }

        $isValidId = false;
        if ($idType == 'CR') {
            $isValidId = preg_match('/^\d{9}$/', $legalIdentification); 
            if (!$isValidId) {
                echo json_encode(['status' => 'error', 'error_code' => 'invalid_costa_rica_id', 'message' => 'Identificación de Costa Rica inválida. Debe contener exactamente 9 dígitos.']);
                exit();
            }
        } elseif ($idType == 'foreign') {
           
            $isValidId = preg_match('/^[a-zA-Z0-9]{6,12}$/', $legalIdentification);
            if (!$isValidId) {
                echo json_encode(['status' => 'error', 'error_code' => 'invalid_foreign_id', 'message' => 'Identificación extranjera inválida. Debe contener entre 6 y 12 caracteres alfanuméricos.']);
                exit();
            }
        }


        if (!empty($name) && is_numeric($name)) {
            echo json_encode(['status' => 'error', 'message' => 'El nombre contiene caracteres inválidos']);
            exit();
        }
        // Apellido
        if (!empty($surnames) && is_numeric($surnames)) {
            echo json_encode(['status' => 'error', 'message' => 'Los apellidos contienen caracteres inválidos']);
            exit();
        }

        $phone = str_replace('-', '', $phone); // Elimina el guion de la máscara

if (!empty($phone) && !preg_match('/^\d{8}$/', $phone)) {
    echo json_encode(['status' => 'error', 'error_code' => 'invalid_phone', 'message' => 'Número de teléfono inválido. Debe contener exactamente 8 dígitos.']);
    exit();

}
        if (!preg_match('/^[^\s@]+@[^\s@]+\.[^\s@]+$/', $email)) {
            echo json_encode(['status' => 'error', 'error_code' => 'invalid_email', 'message' => 'Formato de correo electrónico inválido']);
            exit();
        }
        
        if (empty($response)) {
            $owner = new Owner(
                0,
                $direction,
                $targetFilePath,
                1,
                0,
                $nickName,
                $encryptedPassword,
                1,
                "Propietario",
                $name,
                $surnames,
                $legalIdentification,
                $phone,
                $email
            );
            error_log("Nickname: " . $nickName);
            $ownerBusiness = new ownerBusiness();
            $result = $ownerBusiness->insertTBOwner($owner);
            if ($result['status'] === 'success') {
                echo json_encode(['status' => 'success', 'message' => 'Propietario añadido correctamente.']);
                exit();
            } else if($result['status'] === 'error'){
                echo json_encode(['status' => 'error', 'message' => 'Fallo al agregar el Usuario: ' . $result['message']]);
                exit();
            }
        }
    } else {
        echo json_encode(['status' => 'error', 'error_code' => 'missing_fields', 'message' => 'Datos incompletos o inválidos']);
        exit();
    }

    if (empty($response)) {
        echo json_encode(['status' => 'error', 'error_code' => 'unknown_error', 'message' => 'Ocurrió un error desconocido']);
        exit();
    }
}



if (isset($_POST['update'])) {
    if (
        isset($_POST['ownerLegalIdentification'], $_POST['ownerEmail'], $_POST['ownerID'], $_POST['idType'])
    ) {
        $name = $_POST['ownerName'] ?? '';
        $surnames = $_POST['ownerSurnames'] ?? '';
        $nickName = $_POST['ownerNickName'] ?? '';
        $legalIdentification = $_POST['ownerLegalIdentification'];
        $phone = $_POST['ownerPhone'] ?? '';
        $email = strtolower(trim($_POST['ownerEmail']));
        $direction = $_POST['ownerDirection'] ?? '';
        $password = $_POST['password'] ?? '';
        $idOwner = $_POST['ownerID'];
        $idUser = $_POST['userID'];
        $idType = $_POST['idType'];

        $photoFileName = '';

        $ownerBusiness = new OwnerBusiness();
        $currentOwner = $ownerBusiness->getTBOwner($idOwner);
        $existingPhotoFileName = $currentOwner->getPhotoURLTBOwner();

        if (isset($_FILES['newImage']) && $_FILES['newImage']['error'] == UPLOAD_ERR_OK) {
            $uploadDir = '../images/';
            $fileName = basename($_FILES['newImage']['name']);
            $targetFilePath = $uploadDir . $fileName;

            $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
            $allowTypes = array('jpg', 'png', 'jpeg', 'gif');

            if (in_array($fileType, $allowTypes)) {
                if (!move_uploaded_file($_FILES['newImage']['tmp_name'], $targetFilePath)) {
                    echo json_encode(['status' => 'error', 'message' => 'Fallo al subir la imagen.']);
                    exit();
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Tipo de archivo de imagen no permitido.']);
                exit();
            }
        } else {
            $photoFileName = $existingPhotoFileName;
        }

        $isValidId = false;
        if ($idType == 'CR') {
            $isValidId = preg_match('/^\d{9}$/', $legalIdentification); 
            if (!$isValidId) {
                echo json_encode(['status' => 'error', 'error_code' => 'invalid_costa_rica_id', 'message' => 'Identificación de Costa Rica inválida. Debe contener exactamente 9 dígitos.']);
                exit();
            }
        } elseif ($idType == 'foreign') {
    
            $isValidId = preg_match('/^[a-zA-Z0-9]{6,12}$/', $legalIdentification);
            if (!$isValidId) {
                echo json_encode(['status' => 'error', 'error_code' => 'invalid_foreign_id', 'message' => 'Identificación extranjera inválida. Debe contener entre 6 y 12 caracteres alfanuméricos.']);
                exit();
            }
        }


        if (!empty($name) && is_numeric($name)) {
            echo json_encode(['status' => 'error', 'message' => 'El nombre contiene caracteres inválidos']);
            exit();
        }
        // Apellido
        if (!empty($surnames) && is_numeric($surnames)) {
            echo json_encode(['status' => 'error', 'message' => 'Los apellidos contienen caracteres inválidos']);
            exit();
        }
        if (!empty($phone) && !preg_match('/^\d{8}$/', $phone)) {
            echo json_encode(['status' => 'error', 'message' => 'Número de teléfono inválido. Debe contener exactamente 8 dígitos.']);
            exit();
        }

        if (!preg_match('/^[^\s@]+@[^\s@]+\.[^\s@]+$/', $email)) {
            echo json_encode(['status' => 'error', 'message' => 'Formato de correo electrónico inválido.']);
            exit();
        }

       
        //$encryptedPassword = !empty($password) ? password_hash($password, PASSWORD_BCRYPT) : $currentOwner->getPassword();

       
        if ($isValidId && !empty($email)) {
            $owner = new Owner(
                $idOwner,
                $direction,
                $photoFileName,
                1,
                $idUser,
                $nickName,
                $password,
                true,
                "Propietario",
                $name,
                $surnames,
                $legalIdentification,
                $phone,
                $email
            );

            $result = $ownerBusiness->updateTBOwner($owner);

            if ($result == 1) {
                echo json_encode(['status' => 'success', 'message' => 'Propietario actualizado correctamente.']);
            } elseif ($result == "Email") {
                echo json_encode(['status' => 'error', 'message' => 'El correo electrónico ya existe.']);
            } elseif ($result == "Phone") {
                echo json_encode(['status' => 'error', 'message' => 'El teléfono ya existe.']);
            } elseif ($result == "LegalId") {
                echo json_encode(['status' => 'error', 'message' => 'La identificación legal ya existe.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Error en la base de datos.']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Datos de identificación o correo electrónico inválidos.']);
        }
        exit();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Campos faltantes en la solicitud.']);
        exit();
    }
}


if (isset($_POST['delete'])) {
    if (isset($_POST['ownerID']) && isset($_POST['userID'])) {
        $idOwner = $_POST['ownerID'];
        $idUser = $_POST['userID'];

        $ownerBusiness = new OwnerBusiness();
        $result = $ownerBusiness->deleteTBOwner($idOwner, $idUser);

        if ($result) {
            echo json_encode(['status' => 'success', 'message' => 'Propietario eliminado correctamente.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error en la base de datos al eliminar el propietario.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Campos ID faltantes.']);
    }
    exit();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Solicitud no válida.']);
    exit();
}