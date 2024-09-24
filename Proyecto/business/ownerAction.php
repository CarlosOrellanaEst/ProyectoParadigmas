<?php
include './ownerBusiness.php';
header('Content-Type: application/json');


if (isset($_POST['create'])) {
    $response = array();
    if (
        isset($_POST['ownerLegalIdentification']) && // Verificamos que el campo 'ownerLegalIdentification' esté presente
        isset($_POST['ownerEmail']) && // Verificamos que el campo 'ownerEmail' esté presente
        isset($_POST['password']) // Verificamos que el campo 'password' esté presente
    ) {
        // Extraemos y limpiamos los datos del formulario
        $name = isset($_POST['ownerName']) ? trim($_POST['ownerName']) : '';
        $surnames = isset($_POST['ownerSurnames']) ? trim($_POST['ownerSurnames']) : '';
        $legalIdentification = trim($_POST['ownerLegalIdentification']);
        $phone = isset($_POST['ownerPhone']) ? trim($_POST['ownerPhone']) : '';
        $email = trim($_POST['ownerEmail']);
        $direction = isset($_POST['ownerDirection']) ? trim($_POST['ownerDirection']) : '';
        $idType = trim($_POST['idType']);
        $password = trim($_POST['password']);

        // Encriptamos la contraseña usando SHA-256
        $hashedPassword = hash('sha256', $password);

        // Procesamiento de la imagen
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
                    $response['status'] = 'error';
                    $response['message'] = 'Fallo al subir la imagen';
                    echo json_encode($response);
                    exit();
                }
            } else {
                $response['status'] = 'error';
                $response['message'] = 'Tipo de archivo no permitido';
                echo json_encode($response);
                exit();
            }
        }

        // Validación de la identificación
        $isValidId = false;
        if ($idType == 'CR') {
            $isValidId = preg_match('/^\d{9}$/', $legalIdentification);
            if (!$isValidId) {
                $response['status'] = 'error';
                $response['message'] = 'Identificación de Costa Rica inválida';
                echo json_encode($response);
                exit();
            }
        } elseif ($idType == 'foreign') {
            $isValidId = preg_match('/^\d+$/', $legalIdentification);
            if (!$isValidId) {
                $response['status'] = 'error';
                $response['message'] = 'Identificación extranjera inválida';
                echo json_encode($response);
                exit();
            }
        }

        // Validación de nombre
        if (!empty($name) && !preg_match('/^[a-zA-Z\s]+$/', $name)) {
            $response['status'] = 'error';
            $response['message'] = 'El nombre contiene caracteres inválidos';
            echo json_encode($response);
            exit();
        }

        // Validación de apellidos
        if (!empty($surnames) && !preg_match('/^[a-zA-Z\s]+$/', $surnames)) {
            $response['status'] = 'error';
            $response['message'] = 'Los apellidos contienen caracteres inválidos';
            echo json_encode($response);
            exit();
        }

        // Validación del teléfono
        if (!empty($phone) && !preg_match('/^\d{8}$/', $phone)) {
            $response['status'] = 'error';
            $response['message'] = 'Número de teléfono inválido';
            echo json_encode($response);
            exit();
        }

        // Validación del correo
        if (!preg_match('/^[^\s@]+@[^\s@]+\.[^\s@]+$/', $email)) {
            $response['status'] = 'error';
            $response['message'] = 'Formato de correo electrónico inválido';
            echo json_encode($response);
            exit();
        }

        // Creamos el objeto Owner, añadiendo la contraseña encriptada
        $owner = new Owner(
            0,                              // idTBOwner
            $direction,                     // directionTBOwner
            $targetFilePath,                // photoURLTBOwner
            1,                              // statusTBOwner
            0,                              // id (tbuserid)
            $name,                          // nickname
            $hashedPassword,                // password (contraseña encriptada)
            1,                              // active
            "Propietario",                  // userType
            $name,                          // name
            $surnames,                      // surnames
            $legalIdentification,           // legalIdentification
            $phone,                         // phone
            $email                          // email
        );

        $ownerBusiness = new ownerBusiness();
        $result = $ownerBusiness->insertTBOwner($owner);

        if ($result['status'] === 'success') {
            header('Content-Type: application/json');
            echo json_encode(['status' => 'success', 'message' => 'Prueba exitosa']);
            $response['message'] = 'Propietario añadido correctamente';
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Fallo al agregar el propietario: ' . $result['message'];
        }
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Datos incompletos o inválidos';
    }

    echo json_encode($response);
    exit();
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