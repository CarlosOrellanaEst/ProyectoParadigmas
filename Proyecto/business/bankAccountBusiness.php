<?php

include_once  '../data/bankAccountData.php';
include_once  '../domain/Owner.php';

class BankAccountBusiness {
    private $bankAccountData;

    public function __construct() {
        $this->bankAccountData = new BankAccountData(); 
    }

    public function insertTbBankAccount($tbBankAccountId, Owner $owner, $accountNumber, $bank, $status) {
        $bankAccount = new BankAccount($tbBankAccountId, $owner, $accountNumber, $bank, $status);
        
        return $this->bankAccountData->insertTbBankAccount($bankAccount);
    }
}
