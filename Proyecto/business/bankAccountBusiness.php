<?php

include_once  '../data/bankAccountData.php';
include_once  '../domain/Owner.php';

class BankAccountBusiness {
    private $bankAccountData;

    public function __construct() {
        $this->bankAccountData = new BankAccountData(); 
    }

    public function insertTbBankAccount($bankAccount) {
        return $this->bankAccountData->insertTbBankAccount($bankAccount);
    }

    public function getAllTBBankAccount() {
        return $this->bankAccountData->getAllTbBankAccount();
    }

    public function deleteTBBankAccount($idBankAccount) {
        return $this->bankAccountData->deleteTbBankAccount($idBankAccount);
    }

    public function updateTBBankAccount($bankAccount) {
        return $this->bankAccountData->updateTbBankAccount($bankAccount);
    }

    public function getOneTBBankAccount($idBankAccount) {
        return $this->bankAccountData->getTbBankAccount($idBankAccount);
    }
}
