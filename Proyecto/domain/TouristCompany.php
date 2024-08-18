<?php
class touristCompany{

    private $tbtouristcompanyid;
    private $tbtouristcompanylegalname;
    private $tbtouristcompanymagicname;
    private $tbtouristcompanyowner;
    private $tbtouristcompanycompanyType;
    private $tbtouristcompanystatus;

    public function __construct($tbtouristcompanyid, $tbtouristcompanylegalname, $tbtouristcompanymagicname, $tbtouristcompanyowner, $tbtouristcompanycompanyType, $tbtouristcompanystatus){
        $this->tbtouristcompanyid = $tbtouristcompanyid;
        $this->tbtouristcompanylegalname = $tbtouristcompanylegalname;
        $this->tbtouristcompanymagicname = $tbtouristcompanymagicname;
        $this->tbtouristcompanyowner = $tbtouristcompanyowner;
        $this->tbtouristcompanycompanyType = $tbtouristcompanycompanyType;
        $this->tbtouristcompanystatus = $tbtouristcompanystatus;
    }

    public function getId(){
        return $this->tbtouristcompanyid;
    }

    public function getLegalName(){
        return $this->tbtouristcompanylegalname;
    }

    public function getMagicName(){
        return $this->tbtouristcompanymagicname;
    }

    public function getOwner(){
        return $this->tbtouristcompanyowner;
    }

    public function getCompanyType(){
        return $this->tbtouristcompanycompanyType;
    }

    public function getStatus(){
        return $this->tbtouristcompanystatus;
    }

    public function setId($tbtouristcompanyid){
        $this->tbtouristcompanyid = $tbtouristcompanyid;
    }
    
    public function setLegalName($tbtouristcompanylegalname){
        $this->tbtouristcompanylegalname = $tbtouristcompanylegalname;
    }

    public function setMagicName($tbtouristcompanymagicname){
        $this->tbtouristcompanymagicname = $tbtouristcompanymagicname;
    }

    public function setOwner($tbtouristcompanyowner){
        $this->tbtouristcompanyowner = $tbtouristcompanyowner;
    }

    public function setCompanyType($tbtouristcompanycompanyType){
        $this->tbtouristcompanycompanyType = $tbtouristcompanycompanyType;
    }

    public function setStatus($tbtouristcompanystatus){
        $this->tbtouristcompanystatus = $tbtouristcompanystatus;
    }

    






    

}