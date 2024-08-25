<?php
include './OwnerBusiness.php';

if (isset($_POST['create'])) {
    if (isset($_POST['ownerName']) && isset($_POST['ownerSurnames']) && isset($_POST['ownerLegalIdentification']) && isset($_POST['ownerPhone']) && isset($_POST['ownerEmail']) && isset($_POST['ownerDirection']) && isset($_FILES['imagen']) && $_FILES['imagen']['error'] == UPLOAD_ERR_OK && isset($_POST['idType'])) {

        $name = $_POST['ownerName'];
        $surnames = $_POST['ownerSurnames'];
        $legalIdentification = $_POST['ownerLegalIdentification'];
        $phone = $_POST['ownerPhone'];
        $email = $_POST['ownerEmail'];
        $direction = $_POST['ownerDirection'];
        $idType = $_POST['idType'];

        // Configuración para la subida de imágenes
        $uploadDir = '../images/';
        $fileName = basename($_FILES['imagen']['name']);
        $targetFilePath = $uploadDir . $fileName;

        // Validación del tipo de archivo
        $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
        $allowTypes = array('jpg', 'png', 'jpeg', 'gif');

        if (in_array($fileType, $allowTypes)) {
            if (move_uploaded_file($_FILES['imagen']['tmp_name'], $targetFilePath)) {
                // Imagen subida exitosamente

                // Validación del campo de identificación legal
                $isValidId = false;
                if ($idType == 'CR') {
                    // Validación para cédula nacional de Costa Rica (9 dígitos)
                    $isValidId = preg_match('/^\d{9}$/', $legalIdentification);
                    if (!$isValidId) {
                        header("location: ../view/ownerView.php?error=invalidCostaRicaId");
                        exit();
                    }
                } elseif ($idType == 'foreign') {
                    // Validación para identificación extranjera (8 a 12 dígitos)
                    $isValidId = preg_match('/^\d{8,12}$/', $legalIdentification);
                    if (!$isValidId) {
                        header("location: ../view/ownerView.php?error=invalidForeignId");
                        exit();
                    }
                }

                // Validar que el nombre y apellidos solo contengan letras y espacios
                if (!preg_match('/^[a-zA-Z\s]+$/', $name) || !preg_match('/^[a-zA-Z\s]+$/', $surnames)) {
                    header("location: ../view/ownerView.php?error=numberFormat");
                    exit();
                }

                // Validación del número de teléfono (8 dígitos)
                if (!preg_match('/^\d{8}$/', $phone)) {
                    header("location: ../view/ownerView.php?error=invalidPhone");
                    exit();
                }

                // Validación del correo electrónico
                if (!preg_match('/[0-9]+.*@/', $email)) {
                    header("Location: ../view/ownerView.php?error=invalidEmailFormat");
                    exit();
                }

                $owner = new Owner(0, $direction, $name, $surnames, $legalIdentification, $phone, $email, $targetFilePath, 1);  // Incluyendo la ruta de la imagen
                $ownerBusiness = new OwnerBusiness();

                $result = $ownerBusiness->insertTBOwner($owner);

                if ($result == 1) {
                    header("location: ../view/ownerView.php?success=inserted");
                    exit();
                } else if ($result == "Email") {
                    header("location: ../view/ownerView.php?error=alreadyexists");
                    exit();
                } else if ($result == "Phone") {
                    header("location: ../view/ownerView.php?error=phonealreadyexists");
                    exit();
                } else if ($result == "LegalId") {
                    header("location: ../view/ownerView.php?error=legalidalreadyexists");
                    exit();
                } else {
                    header("location: ../view/ownerView.php?error=dbError");
                    exit();
                }
            } else {
                header("location: ../view/ownerView.php?error=imageUploadFailed");
                exit();
            }
        } else {
            header("location: ../view/ownerView.php?error=invalidFileType");
            exit();
        }
    } else {
        header("location: ../view/ownerView.php?error=error");
        exit();
    }
}



if (isset($_POST['update'])) {
    if (isset($_POST['ownerName'], $_POST['ownerSurnames'], $_POST['ownerLegalIdentification'], $_POST['ownerPhone'], $_POST['ownerEmail'], $_POST['ownerDirection'], $_POST['ownerID'], $_POST['idType'])) {
        $name = $_POST['ownerName'];
        $surnames = $_POST['ownerSurnames'];
        $legalIdentification = $_POST['ownerLegalIdentification'];
        $phone = $_POST['ownerPhone'];
        $email = $_POST['ownerEmail'];
        $direction = $_POST['ownerDirection'];
        $id = $_POST['ownerID'];
        $idType = $_POST['idType'];

        // Variable para almacenar el nombre del archivo de la imagen
        $photoFileName = '';

        // Obtener la URL actual de la foto
        $ownerBusiness = new OwnerBusiness();
        $currentOwner = $ownerBusiness->getTBOwner($id);
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
            // Validación de cédula extranjera (8 a 12 dígitos)
            $isValidId = preg_match('/^\d{8,12}$/', $legalIdentification);
            if (!$isValidId) {
                header("Location: ../view/ownerView.php?error=invalidForeignId");
                exit();
            }
        }

        // Validar que el nombre y apellidos solo contengan letras y espacios
        if (!preg_match('/^[a-zA-Z\s]+$/', $name) || !preg_match('/^[a-zA-Z\s]+$/', $surnames)) {
            header("Location: ../view/ownerView.php?error=numberFormat");
            exit();
        }

        // Validar teléfono (8 dígitos)
        if (!preg_match('/^\d{8}$/', $phone)) {
            header("Location: ../view/ownerView.php?error=invalidPhone");
            exit();
        }

        // Validar correo electrónico
        if (!preg_match('/[0-9]+.*@/', $email)) {
            header("Location: ../view/ownerView.php?error=invalidEmailFormat");
            exit();
        }

        if (strlen($name) > 0 && $isValidId) {
            // Crear objeto Owner con la URL de la imagen (nueva o existente)
            $owner = new Owner($id, $direction, $name, $surnames, $legalIdentification, $phone, $email, $photoFileName, 1);

            // Crear instancia de OwnerBusiness
            $ownerBusiness = new OwnerBusiness();
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

    if (isset($_POST['ownerID'])) {
        $id = $_POST['ownerID'];
        $ownerBusiness = new OwnerBusiness();
        $result = $ownerBusiness ->deleteTBOwner($id);

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
