<?php

include_once './paymentTypeBusiness.php';
include_once '../domain/Owner.php';
include_once '../domain/PaymentType.php';

$response = array();


if (isset($_POST['accountNumber'])) {
    $ownerId = trim($_POST['ownerId']);
    $ownerName = trim(((isset($_POST['ownerName'])) ? $_POST['ownerName'] : ''));
    $accountNumber = trim($_POST['accountNumber']);
    $bank = trim($_POST['sinpeNumber']);

    if (empty($accountNumber)) {
        $response['status'] = 'error';
        $response['message'] = 'El número de cuenta no puede estar vacío';
    } else {
        $paymentType = new PaymentType(0, $ownerId, $accountNumber, $bank, 1);
        $paymentTypeBusiness = new paymentTypeBusiness();

        // Verificamos si el número SINPE no está vacío y si contiene solo números
        if (!empty($bank)) {
            if (!is_numeric($bank)) {
                $response['status'] = 'error';
                $response['message'] = 'El número de SINPE debe ser numérico o puede estar vacío.';
            } else if (!preg_match('/^\d{8}$/', $bank)) {
                $response['status'] = 'error';
                $response['message'] = 'El número de SINPE debe contener exactamente 8 dígitos.';
            }
        }

        if (!isset($response['status'])) { // Solo procedemos si no se encontró ningún error
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

if (isset($_POST['accountNumber'])) {
    $ownerId = trim($_POST['ownerId']);
    $ownerName = trim(((isset($_POST['ownerName'])) ? $_POST['ownerName'] : ''));
    $accountNumber = trim($_POST['accountNumber']);
    $bank = trim($_POST['sinpeNumber']);

    if (empty($accountNumber)) {
        $response['status'] = 'error';
        $response['message'] = 'El número de cuenta no puede estar vacío';
    } else {
        $paymentType = new PaymentType(0, $ownerId, $accountNumber, $bank, 1);
        $paymentTypeBusiness = new paymentTypeBusiness();

        // Verificamos si el número SINPE no está vacío y si contiene solo números
        if (!empty($bank)) {
            if (!is_numeric($bank)) {
                $response['status'] = 'error';
                $response['message'] = 'El número de SINPE debe ser numérico o puede estar vacío.';
            } else if (!preg_match('/^\d{8}$/', $bank)) {
                $response['status'] = 'error';
                $response['message'] = 'El número de SINPE debe contener exactamente 8 dígitos.';
            }
        }
        
        if (!isset($response['status'])) { // Solo procedemos si no se encontró ningún error
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

/*if (isset($_POST['accountNumber'])) {
    $ownerId = trim($_POST['ownerId']);
    $ownerName = trim(((isset($_POST['ownerName'])) ? $_POST['ownerName'] : ''));
    $accountNumber = trim($_POST['accountNumber']);
    $bank = trim($_POST['sinpeNumber']);

    if (empty($accountNumber)) {
        $response['status'] = 'error';
        $response['message'] = 'El número de cuenta no puede estar vacío';
    } else {
        $paymentType = new PaymentType(0, $ownerId, $accountNumber, $bank, 1);
        $paymentTypeBusiness = new paymentTypeBusiness();

        // Verificamos si el número SINPE no está vacío y si contiene solo números
        if (!empty($bank) && !is_numeric($bank)) {
            $response['status'] = 'error';
            $response['message'] = 'El número de SINPE debe ser numérico o puede estar vacío.';
        } else {
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
        
        if (!is_numeric($bank)) {
            $response['status'] = 'error';
            $response['message'] = 'El número de SINPE no puede ser letras.';
        
        } else {
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
} */       

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

        // Validación de que el número de cuenta no esté en blanco
        if (empty($AccountNumber)) {
            header("location: ../view/paymentTypeView.php?error=accountRequired");
            exit();
        }

        // Validación de que los campos que deben ser numéricos lo sean
        if (!is_numeric($AccountNumber)) {
            header("location: ../view/paymentTypeView.php?error=numberFormatBAnkAccount");
            exit();
        }

        // Validación de que el SINPE (si no está vacío) sea numérico y tenga exactamente 8 dígitos
        if (!empty($SinpeNumber)) {
            if (!is_numeric($SinpeNumber)) {
                header("location: ../view/paymentTypeView.php?error=invalidSinpe");
                exit();
            } else if (!preg_match('/^\d{8}$/', $SinpeNumber)) {
                header("location: ../view/paymentTypeView.php?error=invalidSinpeFormat");
                exit();
            }
        }

        // Creamos el objeto PaymentType
        $paymentType = new PaymentType($id, 0, $AccountNumber, $SinpeNumber, $Status);
        $paymentTypeBusiness = new paymentTypeBusiness();
        
        // Intentamos realizar la actualización
        $result = $paymentTypeBusiness->update($paymentType);

        if ($result) {
            header("location: ../view/paymentTypeView.php?success=updated");
            exit();
        } else if ($result == null){
            header("location: ../view/paymentTypeView.php?error=sinpeAlreadyExist");
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

/*if (isset($_POST['update'])) {
    if (isset($_POST['SinpeNumber']) && isset($_POST['AccountNumber']) && isset($_POST['Status']) && isset($_POST['tbpaymentTypeid'])) {
        $SinpeNumber = $_POST['SinpeNumber'];
        $AccountNumber = $_POST['AccountNumber'];
        $Status = $_POST['Status'];
        $id = $_POST['tbpaymentTypeid'];

        // Validación de que el número de cuenta no esté en blanco
        if (empty($AccountNumber)) {
            header("location: ../view/paymentTypeView.php?error=accountRequired");
            exit();
        }

        // Validación de que los campos que deben ser numéricos lo sean
        if (!is_numeric($AccountNumber)) {
            header("location: ../view/paymentTypeView.php?error=numberFormatBAnkAccount");
            exit();
        }

        // Validación de que el SINPE (si no está vacío) sea numérico
        if (!empty($SinpeNumber) && !is_numeric($SinpeNumber)) {
            header("location: ../view/paymentTypeView.php?error=invalidSinpe");
            exit();
        } else if (!empty($SinpeNumber) && !preg_match('/^\d{8}$/', $SinpeNumber)) {
            header("location: ../view/paymentTypeView.php?error=invalidSinpeFormat");
            exit();
        }

        // Creamos el objeto PaymentType
        $paymentType = new PaymentType($id, 0, $AccountNumber, $SinpeNumber, $Status);
        $paymentTypeBusiness = new paymentTypeBusiness();
        
        // Intentamos realizar la actualización
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
        header("location: ../view/paymentTypeView.php?error=missingFields");
        exit();
    }
}

/*if (isset($_POST['update'])) {
    if (isset($_POST['SinpeNumber']) && isset($_POST['AccountNumber']) && isset($_POST['Status']) && isset($_POST['tbpaymentTypeid'])) {
        $SinpeNumber = $_POST['SinpeNumber'];
        $AccountNumber = $_POST['AccountNumber'];
        $Status = $_POST['Status'];
        $id = $_POST['tbpaymentTypeid'];

        // Validación de que el número de cuenta no esté en blanco
        if (empty($AccountNumber)) {
            header("location: ../view/paymentTypeView.php?error=accountRequired");
            exit();
        }

        // Validación de que los campos que deben ser numéricos lo sean
        if (!is_numeric($AccountNumber) || !is_numeric($Status) || !is_numeric($id)) {
            header("location: ../view/paymentTypeView.php?error=numberFormat");
            exit();
        }

        // Creamos el objeto PaymentType
        $paymentType = new PaymentType($id, 0, $AccountNumber, $SinpeNumber, $Status);
        $paymentTypeBusiness = new paymentTypeBusiness();
        
        // Intentamos realizar la actualización
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
        header("location: ../view/paymentTypeView.php?error=missingFields");
        exit();
    }
}

if (isset($_POST['update'])) {
    if (isset($_POST['SinpeNumber']) && isset($_POST['AccountNumber']) && isset($_POST['Status']) && isset($_POST['tbpaymentTypeid'])) {
        $SinpeNumber = $_POST['SinpeNumber'];
        $AccountNumber = $_POST['AccountNumber'];
        $Status = $_POST['Status'];
        $id = $_POST['tbpaymentTypeid'];

        if (strlen($SinpeNumber) > 0) {
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
}*/