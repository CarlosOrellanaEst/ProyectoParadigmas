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
                if (move_uploaded_file($_FILES['imagenes']['tmp_name'][$key], $targetFilePath)) {
                    $fileNames[] = basename($fileName);
                } else {
                    header("location: ../view/photoView.php?error=fileUploadError");
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
    // Aquí debes añadir el código para eliminar las imágenes
}


function standardizeImage($sourcePath, $targetPath, $targetWidth = 800, $targetHeight = 600, $quality = 90, $outputFormat = 'jpeg') {
    // Obtener información de la imagen original
    $imageInfo = getimagesize($sourcePath);
    $sourceWidth = $imageInfo[0];
    $sourceHeight = $imageInfo[1];
    $imageType = $imageInfo[2];

    // Crear una imagen base con las dimensiones deseadas
    $targetImage = imagecreatetruecolor($targetWidth, $targetHeight);

    // Mantener la transparencia si la imagen original es PNG o GIF
    if ($imageType == IMAGETYPE_PNG || $imageType == IMAGETYPE_GIF) {
        imagecolortransparent($targetImage, imagecolorallocate($targetImage, 0, 0, 0));
        imagealphablending($targetImage, false);
        imagesavealpha($targetImage, true);
    }

    // Cargar la imagen original según el tipo
    switch ($imageType) {
        case IMAGETYPE_JPEG:
            $sourceImage = imagecreatefromjpeg($sourcePath);
            break;
        case IMAGETYPE_PNG:
            $sourceImage = imagecreatefrompng($sourcePath);
            break;
        case IMAGETYPE_GIF:
            $sourceImage = imagecreatefromgif($sourcePath);
            break;
        default:
            return false; // Tipo de archivo no soportado
    }

    // Redimensionar la imagen
    imagecopyresampled($targetImage, $sourceImage, 0, 0, 0, 0, $targetWidth, $targetHeight, $sourceWidth, $sourceHeight);

    // Guardar la imagen en el formato especificado con la calidad indicada
    switch (strtolower($outputFormat)) {
        case 'jpeg':
        case 'jpg':
            $result = imagejpeg($targetImage, $targetPath, $quality);
            break;
        case 'png':
            $result = imagepng($targetImage, $targetPath);
            break;
        case 'gif':
            $result = imagegif($targetImage, $targetPath);
            break;
        default:
            return false; // Formato de salida no soportado
    }

    // Liberar la memoria
    imagedestroy($sourceImage);
    imagedestroy($targetImage);

    return $result;
}


?>
