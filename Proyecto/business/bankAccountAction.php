<?php

include_once './bankAccountBusiness.php';
include_once '../domain/Owner.php';

if (isset($_POST['create'])) {
    if (isset($_POST['ownerId']) && isset($_POST['accountNumber']) && isset($_POST['bank']) && isset($_POST['status'])) {
        $ownerId = $_POST['ownerId'];
        $ownerName = ((isset($_POST['ownerName']))? $_POST['ownerName']: '');
        
        $accountNumber = $_POST['accountNumber'];
        $bank = $_POST['bank'];
        $status = $_POST['status'];

        // trim()
        if (strlen(trim($accountNumber)) > 0 && strlen(trim($bank)) > 0) {
            if (!is_numeric($bank)) {
                $bankAccount = new BankAccount(0, $ownerId, $accountNumber, $bank, $status);
                
                $bankAccountBusiness = new BankAccountBusiness();

                $result = $bankAccountBusiness->insertTbBankAccount($bankAccount);

                //echo $result;

                if ($result == 1) {
                    header("location: ../view/bankAccountView.php?success=inserted");
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

if (isset($_POST['delete'])) { 
    if (isset($_POST['tbBankAccountID'])) {
        $id = $_POST['tbBankAccountID'];
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
    if (isset($_POST['BankName']) && isset($_POST['AccountNumber']) && isset($_POST['Status']) && isset($_POST['tbBankAccountID'])) {
        $Bankname = $_POST['BankName'];
        $AccountNumber = $_POST['AccountNumber'];
        $Status = $_POST['Status'];
        $id = $_POST['tbBankAccountID'];

        if (strlen($Bankname) > 0) {
            if (!is_numeric($Bankname) && is_numeric($AccountNumber) && is_numeric($Status) && is_numeric($id)) {
                $bankAccount = new BankAccount($id, 0, $AccountNumber, $Bankname, $Status);
                $bankAccountBusiness = new bankAccountBusiness();
                $result = $bankAccountBusiness->updateTBBankAccount($bankAccount);

                if ($result == 1) {
                    header("location: ../view/bankAccountView.php?success=updated");
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