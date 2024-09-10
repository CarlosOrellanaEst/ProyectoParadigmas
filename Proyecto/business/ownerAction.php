<?php
include './ownerBusiness.php';

if (isset($_POST['create'])) {
    $response = array();

    // Verificar si los campos obligatorios están presentes
    if (
        // isset($_POST['legalIdentification']) &&
        // isset($_POST['phone']) &&
        // isset($_POST['email']) &&
        // isset($_POST['direction']) &&
        // isset($_POST['idType'])

        //isset($_POST['name']) && isset($_POST['surnames']) && 
        isset($_POST['legalIdentification']) &&
        //isset($_POST['phone']) && 
        isset($_POST['email']) 
        //&& isset($_POST['direction']) &&
        //isset($_FILES['imagen']) && $_FILES['imagen']['error'] == UPLOAD_ERR_OK && isset($_POST['idType'])
    ) {
        $name = isset($_POST['name']) ? trim($_POST['name']) : '';
        $surnames = isset($_POST['surnames']) ? trim($_POST['surnames']) : '';
        $legalIdentification = trim($_POST['legalIdentification']);
        $phone = trim($_POST['phone']);
        $email = trim($_POST['email']);
        $direction = trim($_POST['direction']);
        $idType = trim($_POST['idType']);

        // Configuración para la subida de imágenes
        $fileUploaded = isset($_FILES['imagen']) && $_FILES['imagen']['error'] == UPLOAD_ERR_OK;
        $targetFilePath = '';
        
        if ($fileUploaded) {
            $uploadDir = '../images/';
            $fileName = basename($_FILES['imagen']['name']);
            $targetFilePath = $uploadDir . $fileName;

            // Validación del tipo de archivo
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

        // Validación del campo de identificación legal
        $isValidId = false;
        if ($idType == 'CR') {
            // Validación para cédula nacional de Costa Rica (9 dígitos)
            $isValidId = preg_match('/^\d{9}$/', $legalIdentification);
            if (!$isValidId) {
                $response['status'] = 'error';
                $response['message'] = 'Identificación de Costa Rica inválida';
                echo json_encode($response);
                exit();
            }
        } elseif ($idType == 'foreign') {
            // Validación para identificación extranjera (8 a 12 dígitos)
            $isValidId = preg_match('/^\d+$/', $legalIdentification);
            if (!$isValidId) {
                header("Location: ../view/ownerView.php?error=invalidForeignId");
                exit();
            }
        }

        // Validar que el nombre y apellidos solo contengan letras y espacios, si están presentes
        if (!empty($name) && !preg_match('/^[a-zA-Z\s]+$/', $name)) {
            $response['status'] = 'error';
            $response['message'] = 'El nombre contiene caracteres inválidos';
            echo json_encode($response);
            exit();
        }

        if (!empty($surnames) && !preg_match('/^[a-zA-Z\s]+$/', $surnames)) {
            $response['status'] = 'error';
            $response['message'] = 'Los apellidos contienen caracteres inválidos';
            echo json_encode($response);
            exit();
        }

        // Validación del número de teléfono (8 dígitos)
        if (!empty($phone) && !preg_match('/^\d{8}$/', $phone)) {
            $response['status'] = 'error';
            $response['message'] = 'Número de teléfono inválido';
            echo json_encode($response);
            exit();
        }

        // Validación del correo electrónico
        if (!preg_match('/^[^\s@]+@[^\s@]+\.[^\s@]+$/', $email)) {
            $response['status'] = 'error';
            $response['message'] = 'Formato de correo electrónico inválido';
            echo json_encode($response);
            exit();
        }

        // Crear objeto Owner
        // $owner = new Owner(0, $direction, $name, $surnames, $legalIdentification, $phone, $email, $targetFilePath, 1);
        $owner = new Owner(0, $direction, $targetFilePath, 1, 0, $name, $name, 1, "Propietario", $name, $surnames, $legalIdentification, $phone, $email);
        $ownerBusiness = new ownerBusiness();
        
        // Llamar al método insertTBOwner
        $result = $ownerBusiness->insertTBOwner($owner);

        // Verificar el resultado
        if ($result['status'] === 'success') {
            $response['status'] = 'success';
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

if (isset($_POST['update'])) {
    if ( /* isset($_POST['ownerLegalIdentification'], $_POST['ownerEmail'], $_POST['ownerID'], $_POST['idType']) 
        isset($_POST['ownerName'], $_POST['ownerSurnames'], $_POST['ownerLegalIdentification'], $_POST['ownerPhone'], $_POST['ownerEmail'], $_POST['ownerDirection'], $_POST['ownerID'], $_POST['userID'],  $_POST['idType'])*/ 
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

        // Variable para almacenar el nombre del archivo de la imagen
        $photoFileName = '';

        // Obtener la URL actual de la foto
        $ownerBusiness = new OwnerBusiness();
        $currentOwner = $ownerBusiness->getTBOwner($idOwner);
        $existingPhotoFileName = $currentOwner->getPhotoURLTBOwner();

        // Verificar si se ha subido una nueva imagen
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
            // No se subió nueva imagen, usar la URL actual de la foto
            $photoFileName = $existingPhotoFileName;
        }

        // Validaciones
        $isValidId = false;
        if ($idType == 'CR') {
            // Validación de cédula nacional de Costa Rica
            $isValidId = preg_match('/^\d{9}$/', $legalIdentification);
            if (!$isValidId) {
                header("Location: ../view/ownerView.php?error=invalidCostaRicaId");
                exit();
            }
        } elseif ($idType == 'foreign') {
            // Validación de cédula extranjera (solo números)
            $isValidId = preg_match('/^\d+$/', $legalIdentification);
            if (!$isValidId) {
                header("Location: ../view/ownerView.php?error=invalidForeignId");
                exit();
            }
        }

        // Validar que el nombre y apellidos solo contengan letras y espacios si no están vacíos
        if (!empty($name) && !preg_match('/^[a-zA-Z\s]+$/', $name)) {
            header("Location: ../view/ownerView.php?error=invalidName");
            exit();
        }
        if (!empty($surnames) && !preg_match('/^[a-zA-Z\s]+$/', $surnames)) {
            header("Location: ../view/ownerView.php?error=invalidSurnames");
            exit();
        }

        // Validar teléfono (si está presente) que tenga 8 dígitos
        if (!empty($phone) && !preg_match('/^\d{8}$/', $phone)) {
            header("Location: ../view/ownerView.php?error=invalidPhone");
            exit();
        }

        // Validar correo electrónico (obligatorio)
        if (!preg_match('/^[^\s@]+@[^\s@]+\.[^\s@]+$/', $email)) {
            header("Location: ../view/ownerView.php?error=invalidEmailFormat");
            exit();
        }

        // Solo proceder si la identificación y el correo son válidos
        if ($isValidId && !empty($email)) {
            // Crear objeto Owner con la URL de la imagen (nueva o existente)

            // $owner = new Owner($id, $direction, $name, $surnames, $legalIdentification, $phone, $email, $photoFileName, 1);
            $owner = new Owner($idOwner, $direction, "", 1, $idUser, "","", true, "Propietario", $name, $surnames, $legalIdentification, $phone, $email);

            // Crear instancia de OwnerBusiness
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


// if (isset($_POST['update'])) {
//     if (isset($_POST['ownerName'], $_POST['ownerSurnames'], $_POST['ownerLegalIdentification'], $_POST['ownerPhone'], $_POST['ownerEmail'], $_POST['ownerDirection'], $_POST['ownerID'], $_POST['userID'],  $_POST['idType'])) {
//         $name = $_POST['ownerName'];
//         $surnames = $_POST['ownerSurnames'];
//         $legalIdentification = $_POST['ownerLegalIdentification'];
//         $phone = $_POST['ownerPhone'];
//         $email = $_POST['ownerEmail'];
//         $direction = $_POST['ownerDirection'];
//         $idOwner = $_POST['ownerID'];
//         $idUser = $_POST['userID'];
//         $idType = $_POST['idType'];

//         // Variable para almacenar el nombre del archivo de la imagen
//         $photoFileName = '';

//         // Obtener la URL actual de la foto
//         $ownerBusiness = new OwnerBusiness();
//         $currentOwner = $ownerBusiness->getTBOwner($id);
//         $existingPhotoFileName = $currentOwner->getPhotoURLTBOwner();

//         // Verificar si se ha subido una nueva imagen
//         if (isset($_FILES['newImage']) && $_FILES['newImage']['error'] == UPLOAD_ERR_OK) {
//             $uploadDir = '../images/';
//             $fileName = basename($_FILES['newImage']['name']);
//             $targetFilePath = $uploadDir . $fileName;

//             $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
//             $allowTypes = array('jpg', 'png', 'jpeg', 'gif');

//             if (in_array($fileType, $allowTypes)) {
//                 if (move_uploaded_file($_FILES['newImage']['tmp_name'], $targetFilePath)) {
//                     $photoFileName = $fileName;
//                 } else {
//                     header("Location: ../view/ownerView.php?error=uploadFailed");
//                     exit();
//                 }
//             } else {
//                 header("Location: ../view/ownerView.php?error=invalidFileType");
//                 exit();
//             }
//         } else {
//             // No se subió nueva imagen, usar la URL actual de la foto
//             $photoFileName = $existingPhotoFileName;
//         }

//         // Validaciones
//         $isValidId = false;
//         if ($idType == 'CR') {
//             // Validación de cédula nacional de Costa Rica
//             $isValidId = preg_match('/^\d{9}$/', $legalIdentification);
//             if (!$isValidId) {
//                 header("Location: ../view/ownerView.php?error=invalidCostaRicaId");
//                 exit();
//             }
//         } elseif ($idType == 'foreign') {
//             // Validación de cédula extranjera (8 a 12 dígitos)
//             $isValidId = preg_match('/^\d+$/', $legalIdentification);
//             if (!$isValidId) {
//                 header("Location: ../view/ownerView.php?error=invalidForeignId");
//                 exit();
//             }
//         }

//         // Validar que el nombre y apellidos solo contengan letras y espacios
//         if (!empty($name) && !preg_match('/^[a-zA-Z\s]+$/', $name)) {
//             header("Location: ../view/ownerView.php?error=invalidName");
//             exit();
//         }
//         if (!empty($surnames) && !preg_match('/^[a-zA-Z\s]+$/', $surnames)) {
//             header("Location: ../view/ownerView.php?error=invalidSurnames");
//             exit();
//         }

//         // Validar teléfono (8 dígitos)
//         if (!empty($phone) && !preg_match('/^\d{8}$/', $phone)) {
//             header("Location: ../view/ownerView.php?error=invalidPhone");
//             exit();
//         }

//         // Validar correo electrónico
//         if (!preg_match('/^[^\s@]+@[^\s@]+\.[^\s@]+$/', $email)) {
//             header("Location: ../view/ownerView.php?error=invalidEmailFormat");
//             exit();
//         }

//         if (strlen($name) > 0 && $isValidId) {
//             // Crear objeto Owner con la URL de la imagen (nueva o existente)
//             $owner = new Owner($idOwner, $direction, "", 1, $idUser, "","", true, "Propietario", $name, $surnames, $legalIdentification, $phone, $email);

//             // Crear instancia de OwnerBusiness
//             $ownerBusiness = new OwnerBusiness();
//             $result = $ownerBusiness->updateTBOwner($owner);

//             if ($result == 1) {
//                 header("Location: ../view/ownerView.php?success=updated");
//                 exit();
//             } else if ($result == "Email") {
//                 header("Location: ../view/ownerView.php?error=alreadyexists");
//                 exit();
//             } else if ($result == "Phone") {
//                 header("Location: ../view/ownerView.php?error=phonealreadyexists");
//                 exit();
//             } else if ($result == "LegalId") {
//                 header("Location: ../view/ownerView.php?error=legalidalreadyexists");
//                 exit();
//             } else {
//                 header("Location: ../view/ownerView.php?error=dbError");
//                 exit();
//             }
//         } else {
//             header("Location: ../view/ownerView.php?error=error");
//             exit();
//         }
//     } else {
//         header("Location: ../view/ownerView.php?error=error");
//         exit();
//     }
// }

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
