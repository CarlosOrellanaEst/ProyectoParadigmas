<?php

include_once  '../data/paymentTypeData.php';
include_once  '../domain/Owner.php';

class paymentTypeBusiness {
    private $paymentTypeData;

    public function __construct() {
        $this->paymentTypeData = new paymentTypeData(); 
    }

    public function insert($paymentType) {
        return $this->paymentTypeData->insertTbPaymentType($paymentType);
    }

    public function getAll() {
        return $this->paymentTypeData->getAllTbPaymentType();
    }

    public function delete($idPaymentType) {
        return $this->paymentTypeData->deleteTbPaymentTYpe($idPaymentType);
    }

    public function update($paymentType) {
        return $this->paymentTypeData->updateTbPaymentType($paymentType);
    }

    public function getOne($idPaymentType) {
        return $this->paymentTypeData->getTbPaymentTypeByAccountNumber($idPaymentType);
    }

    
}
