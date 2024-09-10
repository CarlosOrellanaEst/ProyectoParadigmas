<?php

include_once './paymentTypeBusiness.php';
include_once '../domain/Owner.php';
include_once '../domain/PaymentType.php';
include_once './generalValidations.php';

$response = array();

if (isset($_POST['accountNumber'])) {
    $ownerId = trim($_POST['ownerId']);
    $accountNumber = trim($_POST['accountNumber']);
    $sinpeNumber = trim($_POST['sinpeNumber']); 

    $response = [];
    $generalValidations = new generalValidations();

    if (empty($accountNumber)) {
        $response['status'] = 'error';
        $response['message'] = 'El número de cuenta no puede estar vacío.';
    } else if ($generalValidations->accountNumberFormat($accountNumber)) {
        $response['status'] = 'error';
        $response['message'] = 'La cuenta de banco no cumple con el formato correcto (Ejm: CR12345678901234567890).';
    } else {
        $paymentType = new PaymentType(0, $ownerId, $accountNumber, $sinpeNumber, 1);
        $paymentTypeBusiness = new paymentTypeBusiness();

        if (!empty($sinpeNumber)) {
            if (!is_numeric($sinpeNumber)) {
                $response['status'] = 'error';
                $response['message'] = 'El número de SINPE debe ser numérico o puede estar vacío.';
            } else if (!preg_match('/^\d{8}$/', $sinpeNumber)) {
                $response['status'] = 'error';
                $response['message'] = 'El número de SINPE debe contener exactamente 8 dígitos.';
            }
        }

        if (!isset($response['status'])) {
            $result = $paymentTypeBusiness->insert($paymentType);

            if ($result['status'] === 'success') {
                $response['status'] = 'success';
                $response['message'] = 'Tipo de pago agregado correctamente.';
            } else if ($result['status'] === 'error') {
                $response['status'] = 'error';
                $response['message'] = 'Fallo al agregar el tipo de pago: ' . $result['message'];
            }
        }
    }

    echo json_encode($response);
    exit();
}

if (isset($_POST['delete'])) { 
    if (isset($_POST['tbpaymentTypeid'])) {
        $id = $_POST['tbpaymentTypeid'];
        $paymentTypeBusiness = new paymentTypeBusiness();
        $result = $paymentTypeBusiness -> delete($id);

        if ($result == 1) {
            header("location: ../view/paymentTypeView.php?success=deleted");
        } else {
            header("location: ../view/paymentTypeView.php?error=dbError");
        }
    } else {
        header("location: ../view/paymentTypeView.php?error=emptyField");
    }
} 

if (isset($_POST['update'])) {
    if (isset($_POST['SinpeNumber']) && isset($_POST['AccountNumber']) && isset($_POST['Status']) && isset($_POST['tbpaymentTypeid'])) {
        $SinpeNumber = trim($_POST['SinpeNumber']);
        $AccountNumber = trim($_POST['AccountNumber']);
        $Status = trim($_POST['Status']);
        $id = trim($_POST['tbpaymentTypeid']);
        $generalValidations = new generalValidations();

        if (empty($AccountNumber)) {
            header("location: ../view/paymentTypeView.php?error=accountRequired");
            exit();
        }

        if ($generalValidations->accountNumberFormat($AccountNumber)) {
            header("location: ../view/paymentTypeView.php?error=numberFormatBAnkAccount");
            exit();
        }

        if (!empty($SinpeNumber)) {
            if (!is_numeric($SinpeNumber)) {
                header("location: ../view/paymentTypeView.php?error=invalidSinpe");
                exit();
            } else if (!preg_match('/^\d{8}$/', $SinpeNumber)) {
                header("location: ../view/paymentTypeView.php?error=invalidSinpeFormat");
                exit();
            }
        }

        $paymentType = new PaymentType($id, 0, $AccountNumber, $SinpeNumber, $Status);
        $paymentTypeBusiness = new paymentTypeBusiness();
        
        $result = $paymentTypeBusiness->update($paymentType);

        if ($result) {
            header("location: ../view/paymentTypeView.php?success=updated");
            exit();
        } else if ($result == null){
            header("location: ../view/paymentTypeView.php?error=duplicateEntry");
            exit();
        } else {    
            header("location: ../view/paymentTypeView.php?error=dbError");
            exit();
        }
        
    } else {
        header("location: ../view/paymentTypeView.php?error=missingFields");
        exit();
    }
}