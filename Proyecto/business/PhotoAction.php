<?php

include '../business/PhotoBusiness.php';


if (isset($_POST['create'])) {
    if (isset($_FILES['imagenes']) && !empty($_FILES['imagenes']['name'][0])) {
        $uploadDir = '../images/';
        $fileNames = array();
        $allowTypes = array('jpg', 'png', 'jpeg', 'gif');

        // Verifica que no se suban más de 5 archivos
        if (count($_FILES['imagenes']['name']) > 5) {
            header("location: ../view/photoView.php?error=tooManyFiles");
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
                    header("location: ../view/photoView.php?error=moveFailed");
                    exit();
                }
            } else {
                header("location: ../view/photoView.php?error=invalidFileType");
                exit();
            }
        }

        $photoUrls = implode(',', $fileNames);

        $photoBusiness = new PhotoBusiness();
        $result = $photoBusiness->insertMultiplePhotos($photoUrls);

        if ($result) {
            header("location: ../view/photoView.php?success=inserted");
        } else {
            header("location: ../view/photoView.php?error=insertFailed");
        }
        exit();
    } else {
        header("location: ../view/photoView.php?error=noFile");
        exit();
    }
}



if (isset($_POST['update'])) {
    if (isset($_POST['photoID']) && isset($_POST['imageIndex']) && isset($_FILES['newImage']) && $_FILES['newImage']['error'] == UPLOAD_ERR_OK) {
        $photoID = $_POST['photoID'];
        $imageIndex = $_POST['imageIndex'];
        $existingUrls = $_POST['existingUrls'];
        $photoUrls = explode(',', $existingUrls);
        $uploadDir = '../images/';
        $fileName = basename($_FILES['newImage']['name']);
        $targetFilePath = $uploadDir . $fileName;

        $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
        $allowTypes = array('jpg', 'png', 'jpeg', 'gif');

        if (in_array($fileType, $allowTypes)) {
            if (move_uploaded_file($_FILES['newImage']['tmp_name'], $targetFilePath)) {
                // Obtén la instancia de PhotoBusiness
                $photoBusiness = new PhotoBusiness();

                // Actualiza la URL de la imagen específica
                $result = $photoBusiness->updateTBPhoto($photoID, $imageIndex, $fileName, $photoUrls);

                if ($result) {
                    header("Location: ../view/photoView.php?success=updated");
                    exit();
                } else {
                    header("Location: ../view/photoView.php?error=dbError");
                    exit();
                }
            } else {
                header("Location: ../view/photoView.php?error=uploadFailed");
                exit();
            }
        } else {
            header("Location: ../view/photoView.php?error=invalidFileType");
            exit();
        }
    } else {
        header("Location: ../view/photoView.php?error=noFile");
        exit();
    }
}


if (isset($_POST['delete'])) {
    if (isset($_POST['photoID']) && isset($_POST['imageIndex'])) {
        $photoID = $_POST['photoID'];
        $imageIndex = $_POST['imageIndex'];

        // Validar que photoID e imageIndex sean enteros para evitar inyecciones SQL
        if (filter_var($photoID, FILTER_VALIDATE_INT) !== false && filter_var($imageIndex, FILTER_VALIDATE_INT) !== false) {
            // Obtén la instancia de PhotoBusiness
            $photoBusiness = new PhotoBusiness();

            // Elimina la imagen específica
            $result = $photoBusiness->deleteTBPhoto($photoID, $imageIndex);

            if ($result) {
                header("Location: ../view/photoView.php?success=deleted");
                exit();
            } else {
                header("Location: ../view/photoView.php?error=dbError");
                exit();
            }
        } else {
            header("Location: ../view/photoView.php?error=invalidParameters");
            exit();
        }
    } else {
        header("Location: ../view/photoView.php?error=missingParameters");
        exit();
    }
}




function redimensionarImagen($rutaImagen, $rutaDestino, $ancho, $alto) {
    $comando = "convert {$rutaImagen} -resize {$ancho}x{$alto} {$rutaDestino}";
    exec($comando, $output, $returnVar);
    return $returnVar === 0; // 0 indica éxito
}

