<?php

include './rollBusiness.php';


$response = array();
// para el AJAX de Create
if (isset($_POST['name'])) {
    // Add task functionality
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);

    if (empty($name)) {
        $response['status'] = 'error';
        $response['message'] = 'el nombre del roll no puede estar vacio';
    } else {
        $roll = new Roll(0, $name, $description, 1);

        $rollBusiness = new RollBusiness();
        $result = $rollBusiness->insertTBRoll($roll);

        if ($result) {
            $response['status'] = 'success';
            $response['message'] = 'Roll añadido correctamente';
        } else {
            $response['status'] = 'error';
            $response['message'] = 'fallo al agregar el roll: ' . mysqli_error($connection);
        }
        
        echo json_encode($response);
        exit();
    }
}

if (isset($_POST['update'])) {
    if (isset($_POST['rollName']) && isset($_POST['rollDescription']) && isset($_POST['rollID'])) {
        $name = $_POST['rollName'];
        $description = $_POST['rollDescription'];
        $id = $_POST['rollID'];

        if (strlen($name) > 0) {
            if (!is_numeric($name) && !is_numeric($description) && is_numeric($id)) {
                $roll = new Roll($id, $name, $description);
                $rollBusiness = new RollBusiness();
                $result = $rollBusiness->updateTBRoll($roll);

                if ($result == 1) {
                    header("location: ../view/rollView.php?success=updated");
                    exit();
                } else if ($result == null) {
                    header("location: ../view/rollView.php?error=notExists");
                    exit();
                 } else {
                    header("location: ../view/rollView.php?error=dbError");
                    exit();
                }
            } else {
                header("location: ../view/rollView.php?error=numberFormat");
                exit();
            }
        } else {
            header("location: ../view/rollView.php?error=emptyField");
            exit();
        }
    } else {
        header("location: ../view/rollView.php?error=error");
        exit();
    }
}

if (isset($_POST['delete'])) { 
    if (isset($_POST['rollID'])) {
        $id = $_POST['rollID'];
        $rollBusiness = new RollBusiness();
        $result = $rollBusiness ->deleteTBRoll($id);

        if ($result == 1) {
            header("location: ../view/rollView.php?success=deleted");
        } else {
            header("location: ../view/rollView.php?error=dbError");
        }
    } else {
        header("location: ../view/rollView.php?error=emptyField");
    }
} else {
    header("location: ../view/rollView.php?error=error");
}

?>