<?php

include_once './touristCompanyTypeBusiness.php';
include_once '../domain/TouristCompanyType.php';

$response = array();
if (isset($_POST['nameTouristCompanyType'])) {
    $name = trim($_POST['nameTouristCompanyType']);
    $description = trim($_POST['description']);

    $response = array();

    if (empty($name)) {
        $response['status'] = 'error';
        $response['message'] = 'El nombre de la actividad no puede estar vacío.';
    } else if (is_numeric($name)) {
        $response['status'] = 'error';
        $response['message'] = 'El nombre de la actividad no puede ser números únicamente.';
    } else if (is_numeric($description)) {
        $response['status'] = 'error';
        $response['message'] = 'La descripción no puede ser números únicamente.';
    } else {
        $companyType = new touristCompanyType(0, $name, $description);
        $companyTypeBusiness = new touristCompanyTypeBusiness();

        $result = $companyTypeBusiness->insert($companyType);

        if ($result['status'] === 'success') {
            $response['status'] = 'success';
            $response['message'] = 'Tipo de empresa turística registrada correctamente.';
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Falló al agregar el tipo de empresa turística: ' . $result['message'];
        }
    }

    echo json_encode($response);
    exit();
}

/*if (isset($_POST['nameTouristCompanyType'])) {
    $name = trim($_POST['nameTouristCompanyType']);
    $description = trim($_POST['description']);

    if (empty($name)) {
        $response['status'] = 'error';
        $response['message'] = 'El nombre de la actividad no puede estar vacío';

    } else {
        $companyType = new touristCompanyType(0, $name, $description);
        $companyTypeBusiness = new touristCompanyTypeBusiness();
        
        if (is_numeric($description)) {
            $response['status'] = 'error';
            $response['message'] = 'La descripción no puede ser números unicamente.';
        
        } else {
            if (is_numeric($name)) {
                $response['status'] = 'error';
                $response['message'] = 'El nombre de la actividad no puede ser números unicamente.';
            } else {
                $result = $companyTypeBusiness->insert($companyType);
            
                if ($result['status'] === 'success') {
                    $response['status'] = 'success';
                    $response['message'] = 'Tipo de empresa turística registrada correctamente.';
                
                } else if ($result['status'] === 'error') {
                    $response['status'] = 'error';
                    $response['message'] = 'Fallo al agregar el tipo de empresa turística ' . $result['message'];
                
                }
            }
        }
    } 
    echo json_encode($response);
    exit();
}     */

if (isset($_POST['delete'])) { 
    if (isset($_POST['tbtouristcompanytypeid'])) {
        $id = $_POST['tbtouristcompanytypeid'];
        $companyTypeBusiness = new touristCompanyTypeBusiness();
        $result = $companyTypeBusiness ->delete($id);

        if ($result == 1) {
            header("location: ../view/touristCompanyTypeView.php?success=deleted");
        } else {
            header("location: ../view/touristCompanyTypeView.php?error=dbError");
        }
    } else {
        header("location: ../view/touristCompanyTypeView.php?error=emptyField");
    }
} 

if (isset($_POST['update'])) {
    if (isset($_POST['name']) && isset($_POST['description'])) {
        $name = $_POST['name'];
        $description = $_POST['description'];
        $id = $_POST['tbtouristcompanytypeid'];

        if (strlen($name) > 0) {
            if (!is_numeric($name) && !is_numeric($description) && is_numeric($id)) {
                $companyType = new TouristCompanyType($id, $name, $description);
                $companyTypeBusiness = new touristCompanyTypeBusiness();
                $result = $companyTypeBusiness->update($companyType);

                if ($result == 1) {
                    header("location: ../view/touristCompanyTypeView.php?success=updated");
                    exit();
                } else if ($result == null) {
                    header("location: ../view/touristCompanyTypeView.php?error=alreadyexists");
                    exit();
                } else {
                    header("location: ../view/touristCompanyTypeView.php?error=dbError");
                    exit();
                }
            } else {
                header("location: ../view/touristCompanyTypeView.php?error=numberFormat");
                exit();
            }
        } else {
            header("location: ../view/touristCompanyTypeView.php?error=emptyField");
            exit();
        }
    } else {
        header("location: ../view/touristCompanyTypeView.php?error=error");
        exit();
    }
}