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

    $generalValidations = new generalValidations();

    if (empty($accountNumber)) {
        echo json_encode(['status' => 'error', 'error_code' => 'account_required', 'message' => 'El número de cuenta no puede estar vacío.']);
        exit();
    } else if ($generalValidations->accountNumberFormat($accountNumber)) {
        echo json_encode(['status' => 'error', 'error_code' => 'invalid_account_format', 'message' => 'La cuenta de banco no cumple con el formato correcto (Ejm: CR12345678901234567890).']);
        exit();
    } else {
        $paymentType = new PaymentType(0, $ownerId, $accountNumber, $sinpeNumber, 1);
        $paymentTypeBusiness = new paymentTypeBusiness();

        if (!empty($sinpeNumber)) {
            if (!is_numeric($sinpeNumber)) {
                echo json_encode(['status' => 'error', 'error_code' => 'invalid_sinpe_number', 'message' => 'El número de SINPE debe ser numérico o puede estar vacío.']);
                exit();
            } else if (!preg_match('/^\d{8}$/', $sinpeNumber)) {
                echo json_encode(['status' => 'error', 'error_code' => 'invalid_sinpe_format', 'message' => 'El número de SINPE debe contener exactamente 8 dígitos.']);
                exit();
            }
        }

        // Si no hay errores, proceder a insertar
        $result = $paymentTypeBusiness->insert($paymentType);

        if ($result['status'] === 'success') {
            echo json_encode(['status' => 'success', 'message' => 'Tipo de pago agregado correctamente.']);
        } else if ($result['status'] === 'error') {
            echo json_encode(['status' => 'error', 'error_code' => 'db_error', 'message' => 'Fallo al agregar el tipo de pago: ' . $result['message']]);
        }
        exit();
    }
}

if (isset($_POST['delete'])) { 
    if (isset($_POST['tbpaymentTypeid'])) {
        $id = $_POST['tbpaymentTypeid'];
        $paymentTypeBusiness = new paymentTypeBusiness();
        $result = $paymentTypeBusiness->delete($id);

        if ($result == 1) {
            echo json_encode(['status' => 'success', 'message' => 'Tipo de pago eliminado correctamente.']);
        } else {
            echo json_encode(['status' => 'error', 'error_code' => 'db_delete_error', 'message' => 'Error en la base de datos al eliminar el tipo de pago.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'error_code' => 'missing_id', 'message' => 'Campo ID vacío.']);
    }
    exit();
} 

if (isset($_POST['update'])) {
    if (isset($_POST['SinpeNumber']) && isset($_POST['AccountNumber']) && isset($_POST['Status']) && isset($_POST['tbpaymentTypeid'])) {
        $SinpeNumber = trim($_POST['SinpeNumber']);
        $AccountNumber = trim($_POST['AccountNumber']);
        $Status = trim($_POST['Status']);
        $id = trim($_POST['tbpaymentTypeid']);
        $generalValidations = new generalValidations();

        if (empty($AccountNumber)) {
            echo json_encode(['status' => 'error', 'error_code' => 'account_required', 'message' => 'El número de cuenta es obligatorio.']);
            exit();
        }

        if ($generalValidations->accountNumberFormat($AccountNumber)) {
            echo json_encode(['status' => 'error', 'error_code' => 'invalid_account_format', 'message' => 'El número de cuenta bancaria no cumple con el formato correcto.']);
            exit();
        }

        if (!empty($SinpeNumber)) {
            if (!is_numeric($SinpeNumber)) {
                echo json_encode(['status' => 'error', 'error_code' => 'invalid_sinpe_number', 'message' => 'El número de SINPE debe ser numérico.']);
                exit();
            } else if (!preg_match('/^\d{8}$/', $SinpeNumber)) {
                echo json_encode(['status' => 'error', 'error_code' => 'invalid_sinpe_format', 'message' => 'El número de SINPE debe contener exactamente 8 dígitos.']);
                exit();
            }
        }

        $paymentType = new PaymentType($id, 0, $AccountNumber, $SinpeNumber, $Status);
        $paymentTypeBusiness = new paymentTypeBusiness();
        
        $result = $paymentTypeBusiness->update($paymentType);

        if ($result) {
            echo json_encode(['status' => 'success', 'message' => 'Tipo de pago actualizado correctamente.']);
        } else if ($result == null) {
            echo json_encode(['status' => 'error', 'error_code' => 'duplicate_entry', 'message' => 'Entrada duplicada en la base de datos.']);
        } else {    
            echo json_encode(['status' => 'error', 'error_code' => 'db_update_error', 'message' => 'Error en la base de datos al actualizar el tipo de pago.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'error_code' => 'missing_fields', 'message' => 'Campos faltantes.']);
    }
    exit();
}
