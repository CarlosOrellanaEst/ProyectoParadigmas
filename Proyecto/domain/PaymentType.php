<?php

include_once '../domain/Owner.php';

class PaymentType {
    private $tbPaymentTypeId;
    private $ownerId;
    private $accountNumber;
    private $status;
    private $sinpeNumber;
    private $ownerFullName;

    public function __construct ($tbPaymentTypeId, $ownerId, $accountNumber,$sinpeNumber, $status){
        $this->tbPaymentTypeId = $tbPaymentTypeId;
        $this->ownerId = $ownerId;
        $this->accountNumber = $accountNumber;
        $this->status = $status;
        $this->sinpeNumber = $sinpeNumber;
    }

    public function setOwnerFullName($fullName) {
        $this->ownerFullName = $fullName;
    }

    public function getOwnerFullName() {
        return $this->ownerFullName;
    }

    public function getTbPaymentTypeId(){
        return $this->tbPaymentTypeId;
    }   
    public function setTbPaymentTypeId($tbPaymentTypeId){
        $this->tbPaymentTypeId = $tbPaymentTypeId;
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

    public function getStatus(){
        return $this->status;
    }
    public function setStatus($status){
        $this->status = $status;
    }

    public function getSinpeNumber(){
        return $this->sinpeNumber;
    }
    public function setSinpeNumber($sinpeNumber){
        $this->sinpeNumber = $sinpeNumber;
    }
}