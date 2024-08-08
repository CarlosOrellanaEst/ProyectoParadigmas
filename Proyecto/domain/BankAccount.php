<?php

include_once '../domain/Owner.php';

class BankAccount {
    private $tbBankAccountId;
    private $owner;
    private $accountNumber;
    private $bank;
    private $status;

    public function __construct ($tbBankAccountId, Owner $owner, $accountNumber, $bank, $status){
        $this->tbBankAccountId = $tbBankAccountId;
        $this->owner = $owner;
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

    public function getOwner() {
        return $this->owner;
    }
    public function setOwner(Owner $owner) {
        $this->owner = $owner;
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