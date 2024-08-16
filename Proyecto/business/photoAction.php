<?php

include '../business/photoBusiness.php';

if (isset($_POST['create'])) {
    // Verifica si se ha subido un archivo y no hubo errores
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == UPLOAD_ERR_OK) {
        $uploadDir = '../images/';
        $fileName = basename($_FILES['imagen']['name']);
        $targetFilePath = $uploadDir . $fileName;

        $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
        $allowTypes = array('jpg', 'png', 'jpeg', 'gif');
       
        if (in_array($fileType, $allowTypes)) {
            if (move_uploaded_file($_FILES['imagen']['tmp_name'], $targetFilePath)) {
                $photo = new Photo();
                $photo->setUrlTBPhoto($fileName);

                $photoBusiness = new PhotoBusiness();
                $result = $photoBusiness->insertTBPhoto($photo);

                if ($result) {
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
}
if (isset($_POST['update'])) {
    if (isset($_POST['photoID']) && isset($_FILES['newImage']) && $_FILES['newImage']['error'] == UPLOAD_ERR_OK) {
        $photoID = $_POST['photoID'];
        $uploadDir = '../images/';
        $fileName = basename($_FILES['newImage']['name']);
        $targetFilePath = $uploadDir . $fileName;

        $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
        $allowTypes = array('jpg', 'png', 'jpeg', 'gif');

        if (in_array($fileType, $allowTypes)) {
            if (move_uploaded_file($_FILES['newImage']['tmp_name'], $targetFilePath)) {
                $photo = new Photo($photoID, $fileName);

                $photoBusiness = new photoBusiness();
                $result = $photoBusiness->updateTBPhoto($photo);

                if ($result) {
                    header("location: ../view/photoView.php?success=updated");
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
} else if (isset($_POST['delete'])) { 
    if (isset($_POST['photoID'])) {
        $id = $_POST['photoID'];
        $photoBusiness = new photoBusiness();
        $result = $photoBusiness->deleteTBPhoto($id);

        if ($result) {
            header("location: ../view/photoView.php?success=deleted");
        } else {
            header("location: ../view/photoView.php?error=dbError");
        }
    } else {
        header("location: ../view/photoView.php?error=emptyField");
    }
} else {
    header("location: ../view/photoView.php?error=error");
}
?>
