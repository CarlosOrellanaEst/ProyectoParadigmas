<?php

include_once './paymentTypeBusiness.php';
include_once '../domain/Owner.php';
include_once '../domain/PaymentType.php';

$response = array();

if (isset($_POST['accountNumber'])) {
    $ownerId = trim($_POST['ownerId']);
    $ownerName = trim(((isset($_POST['ownerName']))? $_POST['ownerName']: ''));
    $accountNumber = trim($_POST['accountNumber']);
    $bank = trim($_POST['sinpeNumber']);

    if (empty($accountNumber)) {
        $response['status'] = 'error';
        $response['message'] = 'El número de cuenta no puede estar vacío';

    } else {
        $paymentType = new PaymentType(0, $ownerId, $accountNumber, $bank, 1);
        $paymentTypeBusiness = new paymentTypeBusiness();
        
        if (is_numeric($bank)) {
            $response['status'] = 'error';
            $response['message'] = 'El nombre del banco no puede ser números unicamente.';
        
        } else {
            $result = $paymentTypeBusiness->insert($paymentType);
            
            if ($result['status'] === 'success') {
                $response['status'] = 'success';
                $response['message'] = 'Cuenta de banco registrada correctamente.';
            
            } else if ($result['status'] === 'error') {
                $response['status'] = 'error';
                $response['message'] = 'Fallo al agregar la cuenta de banco: ' . $result['message'];
            
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
        $SinpeNumber = $_POST['SinpeNumber'];
        $AccountNumber = $_POST['AccountNumber'];
        $Status = $_POST['Status'];
        $id = $_POST['tbpaymentTypeid'];

        if (strlen($Bankname) > 0) {
            if (!is_numeric($SinpeNumber) && is_numeric($AccountNumber) && is_numeric($Status) && is_numeric($id)) {
                $paymentType = new PaymentType($id, 0, $AccountNumber, $SinpeNumber, $Status);
                $paymentTypeBusiness = new paymentTypeBusiness();
                $result = $paymentTypeBusiness->update($paymentType);

                if ($result == 1) {
                    header("location: ../view/paymentTypeView.php?success=updated");
                    exit();
                } else if ($result == null) {
                    header("location: ../view/paymentTypeView.php?error=alreadyexists");
                    exit();
                } else {
                    header("location: ../view/paymentTypeView.php?error=dbError");
                    exit();
                }
            } else {
                header("location: ../view/paymentTypeView.php?error=numberFormat");
                exit();
            }
        } else {
            header("location: ../view/paymentTypeView.php?error=emptyField");
            exit();
        }
    } else {
        header("location: ../view/paymentTypeView.php?error=error");
        exit();
    }
}