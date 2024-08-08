<?php

include '../data/bankAccountData.php';

class bankAccountBusiness {
    private $bankAccountData;

    public function __construct() {
        $this->bankAccountData = new bankAccountData(); 
    }

    public function insertTbBankAccount($bankAccount) {
        return $this->bankAccountData->insertTbBankAccount($bankAccount);
    }
}
