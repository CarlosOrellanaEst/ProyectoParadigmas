<?php

include_once '../domain/Owner.php';

class BankAccount {
    private $tbBankAccountId;
    private $ownerId;
    private $accountNumber;
    private $bank;
    private $status;

    public function __construct ($tbBankAccountId, $ownerId, $accountNumber, $bank, $status){
        $this->tbBankAccountId = $tbBankAccountId;
        $this->ownerId = $ownerId;
        $this->accountNumber = $accountNumber;
        $this->bank = $bank;
        $this->status = $status;
    }

    public function getTbBankAccountId(){
        return $this->tbBankAccountId;
    }   
    public function setTbBankAccountId($tbBankAccountId){
        $this->tbBankAccountId = $tbBankAccountId;
    }

    public function getOwnerId() {
        return $this->ownerId;
    }
    public function setOwnerId($ownerId) {
        $this->ownerId = $ownerId;
    }

    public function getAccountNumber(){
        return $this->accountNumber;
    }
    public function setAccountNumber($accountNumber){
        $this->accountNumber = $accountNumber;
    }

    public function getBank(){
        return $this->bank;
    }
    public function setBank($bank){
        $this->bank = $bank;
    }

    public function getStatus(){
        return $this->status;
    }
    public function setStatus($status){
        $this->status = $status;
    }
}