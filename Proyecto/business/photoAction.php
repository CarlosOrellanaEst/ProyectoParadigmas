<?php

include './photoBusiness.php';

if (isset($_POST['create'])) {
    // Verifica si se ha subido un archivo y no hubo errores
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == UPLOAD_ERR_OK) {
        $uploadDir = '../uploads/'; // Directorio donde se guardarán las imágenes
        $fileName = basename($_FILES['imagen']['name']);
        $targetFilePath = $uploadDir . $fileName;

        // Verifica si el archivo es de tipo imagen
        $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION)); // Convertir extensión a minúsculas
        $allowTypes = array('jpg', 'png', 'jpeg', 'gif');
        if (in_array($fileType, $allowTypes)) {
            // Sube el archivo al servidor
            if (move_uploaded_file($_FILES['imagen']['tmp_name'], $targetFilePath)) {
                // Inserta la URL en la base de datos
                $photo = new Photo();
                $photo->setUrlTBPhoto($targetFilePath); // Establece el URL de la imagen

                $photoBusiness = new PhotoBusiness();
                $result = $photoBusiness->insertTBPhoto($photo);

                if ($result) { // Si la inserción fue exitosa
                    header("location: ../view/photoView.php?success=inserted");
                    exit();
                } else {
                    header("location: ../view/photoView.php?error=dbError");
                    exit();
                }
            } else {
                header("location: ../view/photoView.php?error=uploadFailed");
                exit();
            }
        } else {
            header("location: ../view/photoView.php?error=invalidFileType");
            exit();
        }
    } else {
        header("location: ../view/photoView.php?error=noFile");
        exit();
    }
} else {
    header("location: ../view/photoView.php?error=error");
    exit();
}
?>
