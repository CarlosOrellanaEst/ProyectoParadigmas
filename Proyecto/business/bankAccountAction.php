<?php

include_once './bankAccountBusiness.php';
include_once '../domain/Owner.php';

if (isset($_POST['create'])) {
    if (isset($_POST['ownerId']) && isset($_POST['ownerName']) && isset($_POST['accountNumber']) && isset($_POST['bank']) && isset($_POST['status'])) {
        $ownerId = $_POST['ownerId'];
        $ownerName = $_POST['ownerName'];
        $accountNumber = $_POST['accountNumber'];
        $bank = $_POST['bank'];
        $status = $_POST['status'];

        if (strlen($accountNumber) > 0 && strlen($bank) > 0) {
            if (!is_numeric($bank)) {
                $owner = new Owner($ownerId, $ownerName);
                
                $bankAccount = new BankAccount(0, $owner, $accountNumber, $bank, $status);
                
                $bankAccountBusiness = new BankAccountBusiness();

                $result = $bankAccountBusiness->insertTbBankAccount($bankAccount);

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
