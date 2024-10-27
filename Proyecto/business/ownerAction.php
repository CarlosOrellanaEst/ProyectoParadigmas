<?php
include './ownerBusiness.php';
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
        $legalIdentification = trim($_POST['ownerLegalIdentification']);
        $phone = isset($_POST['ownerPhone']) ? trim($_POST['ownerPhone']) : '';
        $email = trim($_POST['ownerEmail']);
        $direction = isset($_POST['ownerDirection']) ? trim($_POST['ownerDirection']) : '';
        $idType = trim($_POST['idType']);
        $password = trim($_POST['password']);

        $hashedPassword = hash('sha256', $password);

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
            $isValidId = preg_match('/^\d+$/', $legalIdentification);
            if (!$isValidId) {
                echo json_encode(['status' => 'error', 'error_code' => 'invalid_foreign_id', 'message' => 'Identificación extranjera inválida. Solo se permiten números.']);
                exit();
            }
        }

        if (!empty($name) && !preg_match('/^[a-zA-Z\s]+$/', $name)) {
            echo json_encode(['status' => 'error', 'error_code' => 'invalid_name', 'message' => 'El nombre contiene caracteres inválidos']);
            exit();
        }

        if (!empty($surnames) && !preg_match('/^[a-zA-Z\s]+$/', $surnames)) {
            echo json_encode(['status' => 'error', 'error_code' => 'invalid_surnames', 'message' => 'Los apellidos contienen caracteres inválidos']);
            exit();
        }

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
                $name,
                $hashedPassword,
                1,
                "Propietario",
                $name,
                $surnames,
                $legalIdentification,
                $phone,
                $email
            );

            $ownerBusiness = new ownerBusiness();
            $result = $ownerBusiness->insertTBOwner($owner);

            if ($result['status'] === 'success') {
                echo json_encode(['status' => 'success', 'message' => 'Propietario añadido correctamente.']);
                exit();
            } else {
                echo json_encode(['status' => 'error', 'error_code' => 'db_error', 'message' => 'Fallo al agregar el propietario: ' . $result['message']]);
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





/*

if (isset($_POST['update'])) {
    if ( 
        isset($_POST['ownerLegalIdentification'], $_POST['ownerEmail'], $_POST['ownerID'], $_POST['idType'])
        ) 
    {
        $name = $_POST['ownerName'] ?? '';
        $surnames = $_POST['ownerSurnames'] ?? '';
        $legalIdentification = $_POST['ownerLegalIdentification'];
        $phone = $_POST['ownerPhone'] ?? '';
        $email = $_POST['ownerEmail'];
        $direction = $_POST['ownerDirection'] ?? '';
        $idOwner = $_POST['ownerID'];
        $idUser = $_POST['userID'];
        $idType = $_POST['idType'];

        $photoFileName = '';

        $ownerBusiness = new ownerBusiness();
        $currentOwner = $ownerBusiness->getTBOwner($idOwner);
        $existingPhotoFileName = $currentOwner->getPhotoURLTBOwner();

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
                    header("Location: ../view/ownerView.php?error=uploadFailed");
                    exit();
                }
            } else {
                header("Location: ../view/ownerView.php?error=invalidFileType");
                exit();
            }
        } else {
            $photoFileName = $existingPhotoFileName;
        }

        $isValidId = false;
        if ($idType == 'CR') {
            $isValidId = preg_match('/^\d{9}$/', $legalIdentification);
            if (!$isValidId) {
                header("Location: ../view/ownerView.php?error=invalidCostaRicaId");
                exit();
            }
        } elseif ($idType == 'foreign') {
            $isValidId = preg_match('/^\d+$/', $legalIdentification);
            if (!$isValidId) {
                header("Location: ../view/ownerView.php?error=invalidForeignId");
                exit();
            }
        }

        if (!empty($name) && !preg_match('/^[a-zA-Z\s]+$/', $name)) {
            header("Location: ../view/ownerView.php?error=invalidName");
            exit();
        }
        if (!empty($surnames) && !preg_match('/^[a-zA-Z\s]+$/', $surnames)) {
            header("Location: ../view/ownerView.php?error=invalidSurnames");
            exit();
        }

        if (!empty($phone) && !preg_match('/^\d{8}$/', $phone)) {
            header("Location: ../view/ownerView.php?error=invalidPhone");
            exit();
        }

        if (!preg_match('/^[^\s@]+@[^\s@]+\.[^\s@]+$/', $email)) {
            header("Location: ../view/ownerView.php?error=invalidEmailFormat");
            exit();
        }

        if ($isValidId && !empty($email)) {
            
            $owner = new Owner($idOwner, $direction, "", 1, $idUser, "","", true, "Propietario", $name, $surnames, $legalIdentification, $phone, $email);

            $ownerBusiness = new ownerBusiness();
            $result = $ownerBusiness->updateTBOwner($owner);

            if ($result == 1) {
                header("Location: ../view/ownerView.php?success=updated");
                exit();
            } else if ($result == "Email") {
                header("Location: ../view/ownerView.php?error=alreadyexists");
                exit();
            } else if ($result == "Phone") {
                header("Location: ../view/ownerView.php?error=phonealreadyexists");
                exit();
            } else if ($result == "LegalId") {
                header("Location: ../view/ownerView.php?error=legalidalreadyexists");
                exit();
            } else {
                header("Location: ../view/ownerView.php?error=dbError");
                exit();
            }
        } else {
            header("Location: ../view/ownerView.php?error=error");
            exit();
        }
    } else {
        header("Location: ../view/ownerView.php?error=error");
        exit();
    }
}

if (isset($_POST['delete'])) { 

    if (isset($_POST['ownerID']) && isset($_POST['userID'])) {
        $idOwner = $_POST['ownerID'];
        $idUser = $_POST['userID'];
        echo ("user " . $idUser . " owner " . $idOwner);
        $ownerBusiness = new OwnerBusiness();
        $result = $ownerBusiness->deleteTBOwner($idOwner, $idUser);

        if ($result == 1) {
            header("location: ../view/ownerView.php?success=deleted");
        } else {
            header("location: ../view/ownerView.php?error=dbError");
        }
    } else {
        header("location: ../view/ownerView.php?error=emptyField");
    }
} else {
    header("location: ../view/ownerView.php?error=error");
}
*/