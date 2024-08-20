<?php

include './rollBusiness.php';
/* 
$response = array();
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
    }
} else {
    $response['status'] = 'error';
    $response['message'] = 'peticion invalida';
}

echo json_encode($response);

?> */