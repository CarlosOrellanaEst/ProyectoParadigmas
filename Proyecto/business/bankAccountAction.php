<?php

include_once './bankAccountBusiness.php';
include_once '../domain/Owner.php';

$response = array();

if (isset($_POST['accountNumber'])) {
    $ownerId = trim($_POST['ownerId']);
    $ownerName = trim(((isset($_POST['ownerName']))? $_POST['ownerName']: ''));
    $accountNumber = trim($_POST['accountNumber']);
    $bank = trim($_POST['bank']);
    $status = trim($_POST['status']);

    if (empty($accountNumber)) {
        $response['status'] = 'error';
        $response['message'] = 'El número de cuenta no puede estar vacío';

    } else {
        $bankAccount = new BankAccount(0, $ownerId, $accountNumber, $bank, $status);
        $bankAccountBusiness = new BankAccountBusiness();
        
        if (is_numeric($bank)) {
            $response['status'] = 'error';
            $response['message'] = 'El nombre del banco no puede ser números unicamente.';
        
        } else {
            $result = $bankAccountBusiness->insertTbBankAccount($bankAccount);
            
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
    if (isset($_POST['tbbankaccountid'])) {
        $id = $_POST['tbbankaccountid'];
        $bankBusiness = new bankAccountBusiness();
        $result = $bankBusiness ->deleteTBBankAccount($id);

        if ($result == 1) {
            header("location: ../view/bankAccountView.php?success=deleted");
        } else {
            header("location: ../view/bankAccountView.php?error=dbError");
        }
    } else {
        header("location: ../view/bankAccountView.php?error=emptyField");
    }
} 

if (isset($_POST['update'])) {
    if (isset($_POST['BankName']) && isset($_POST['AccountNumber']) && isset($_POST['Status']) && isset($_POST['tbbankaccountid'])) {
        $Bankname = $_POST['BankName'];
        $AccountNumber = $_POST['AccountNumber'];
        $Status = $_POST['Status'];
        $id = $_POST['tbbankaccountid'];

        if (strlen($Bankname) > 0) {
            if (!is_numeric($Bankname) && is_numeric($AccountNumber) && is_numeric($Status) && is_numeric($id)) {
                $bankAccount = new BankAccount($id, 0, $AccountNumber, $Bankname, $Status);
                $bankAccountBusiness = new bankAccountBusiness();
                $result = $bankAccountBusiness->updateTBBankAccount($bankAccount);

                if ($result == 1) {
                    header("location: ../view/bankAccountView.php?success=updated");
                    exit();
                } else if ($result == null) {
                    header("location: ../view/bankAccountView.php?error=alreadyexists");
                    exit();
                } else {
                    header("location: ../view/bankAccountView.php?error=dbError");
                    exit();
                }
            } else {
                header("location: ../view/bankAccountView.php?error=numberFormat");
                exit();
            }
        } else {
            header("location: ../view/bankAccountView.php?error=emptyField");
            exit();
        }
    } else {
        header("location: ../view/bankAccountView.php?error=error");
        exit();
    }
}