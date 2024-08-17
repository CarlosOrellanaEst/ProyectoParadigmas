<?php
class touristCompany{

    private $id;
    private $legalName;
    private $magicName;
    private $owner;
    private $companyType;

    function __construct($id = 0, $legalName="", $magicName="", $owner="", $companyType=""){

        $this->id=$id;
        $this->legalName=$legalName;
        $this->magicName=$magicName;
        $this->owner=$owner;
        $this->companyType=$companyType;

    }


    public function getId() {
        return $this->id;
    }

    public function getLegalName() {
        return $this->legalName;
    }

    public function getMagicName() {
        return $this->magicName;
    }

    public function getOwner() {
        return $this->owner;
    }

    public function getCompanyType() {
        return $this->companyType;
    }


    public function setId($id) {
        $this->id = $id;
    }

    public function setLegalName($legalName) {
        $this->legalName = $legalName;
    }

    public function setMagicName($magicName) {
        $this->magicName = $magicName;
    }

    public function setOwner($owner) {
        $this->owner = $owner;
    }

    public function setCompanyType($companyType) {
        $this->companyType = $companyType;
    }






    

}