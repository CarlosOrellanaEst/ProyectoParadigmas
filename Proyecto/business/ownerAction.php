<?php

include './OwnerBusiness.php';

if (isset($_POST['create'])) {
    if (isset($_POST['ownerName']) && isset($_POST['ownerSurnames']) && isset($_POST['ownerLegalIdentification']) && isset($_POST['ownerPhone']) && isset($_POST['ownerEmail']) && isset($_POST['ownerDirection']) && isset($_FILES['imagen']) && $_FILES['imagen']['error'] == UPLOAD_ERR_OK) {
      
        $name = $_POST['ownerName'];
        $surnames = $_POST['ownerSurnames'];
        $legalIdentification = $_POST['ownerLegalIdentification'];
        $phone = $_POST['ownerPhone'];
        $email = $_POST['ownerEmail'];
        $direction = $_POST['ownerDirection'];

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

                if (strlen($name) > 0) {
                    if (!is_numeric($name) && !is_numeric($surnames) && ctype_alnum($legalIdentification) && ctype_alnum($phone) && preg_match('/^[\s\S]*$/', $email)) {
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
                        header("location: ../view/ownerView.php?error=numberFormat");
                        exit();
                    }
                } else {
                    header("location: ../view/ownerView.php?error=emptyField");
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
    if (isset($_POST['ownerName'], $_POST['ownerSurnames'], $_POST['ownerLegalIdentification'], $_POST['ownerPhone'], $_POST['ownerEmail'], $_POST['ownerDirection'], $_POST['ownerID'])) {
        $name = $_POST['ownerName'];
        $surnames = $_POST['ownerSurnames'];
        $legalIdentification = $_POST['ownerLegalIdentification'];
        $phone = $_POST['ownerPhone'];
        $email = $_POST['ownerEmail'];
        $direction = $_POST['ownerDirection'];
        $id = $_POST['ownerID'];

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
                    header("location: ../view/ownerView.php?error=uploadFailed");
                    exit();
                }
            } else {
                header("location: ../view/ownerView.php?error=invalidFileType");
                exit();
            }
        } else {
            // No se subió nueva imagen, usar la URL actual de la foto
            $photoFileName = $existingPhotoFileName;
        }

        // Validaciones
        if (strlen($name) > 0) {
            if (!is_numeric($name) && !is_numeric($surnames) && ctype_alnum($legalIdentification) && ctype_alnum($phone) && filter_var($email, FILTER_VALIDATE_EMAIL) && is_numeric($id)) {
                // Crear objeto Owner con la URL de la imagen (nueva o existente)
                $owner = new Owner($id, $direction, $name, $surnames, $legalIdentification, $phone, $email, $photoFileName, 1);

                // Crear instancia de OwnerBusiness
                $ownerBusiness = new OwnerBusiness();
                $result = $ownerBusiness->updateTBOwner($owner);

                if ($result == 1) {
                    header("location: ../view/ownerView.php?success=updated");
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
                header("location: ../view/ownerView.php?error=numberFormat");
                exit();
            }
        } else {
            header("location: ../view/ownerView.php?error=emptyField");
            exit();
        }
    } else {
        header("location: ../view/ownerView.php?error=error");
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
