<?php
class TouristCompany{

    private $tbtouristcompanyid;
    private $tbtouristcompanylegalname;
    private $tbtouristcompanymagicname;
    private $tbtouristcompanyowner;
    private $tbtouristcompanycompanyType;
    private $tbtouristcompanycustomcompanytype;
    private $tbtouristcompanyurl;
    private $tbtouristcompanystatus;
    private $photos; 
    private $allTouristCompanyType;

    private $allTouristCompanyType;

    public function __construct($tbtouristcompanyid, $tbtouristcompanylegalname, $tbtouristcompanymagicname, $tbtouristcompanyowner, $tbtouristcompanycompanyType, $tbtouristcompanyurl, $tbtouristcompanystatus){
        $this->tbtouristcompanyid = $tbtouristcompanyid;
        $this->tbtouristcompanylegalname = $tbtouristcompanylegalname;
        $this->tbtouristcompanymagicname = $tbtouristcompanymagicname;
        $this->tbtouristcompanyowner = $tbtouristcompanyowner;
        $this->tbtouristcompanycompanyType = $tbtouristcompanycompanyType;
        $this->tbtouristcompanyurl = $tbtouristcompanyurl;
        $this->tbtouristcompanystatus = $tbtouristcompanystatus;
        $this->photos = []; 
        $this->allTouristCompanyType = [];
        
    }

    public function setTbtouristcompanyid($tbtouristcompanyid) {
        $this->tbtouristcompanyid = $tbtouristcompanyid;
    }

    public function setTbtouristcompanylegalname($tbtouristcompanylegalname) {
        $this->tbtouristcompanylegalname = $tbtouristcompanylegalname;
    }

    public function setTbtouristcompanymagicname($tbtouristcompanymagicname) {
        $this->tbtouristcompanymagicname = $tbtouristcompanymagicname;
    }

    public function setTbtouristcompanyowner($tbtouristcompanyowner) {
        $this->tbtouristcompanyowner = $tbtouristcompanyowner;
    }

    public function setTbtouristcompanycompanyType($tbtouristcompanycompanyType) {
        $this->tbtouristcompanycompanyType = $tbtouristcompanycompanyType;
    }

    public function setTbtouristcompanycustomcompanyType($tbtouristcompanycustomcompanyType) { // customCompanyType
        $this->tbtouristcompanycustomcompanytype = $tbtouristcompanycustomcompanyType;
    }

    public function setTbtouristcompanyurl($tbtouristcompanyurl) {
        $this->tbtouristcompanyurl = $tbtouristcompanyurl;
    }

    public function setTbtouristcompanystatus($tbtouristcompanystatus) {
        $this->tbtouristcompanystatus = $tbtouristcompanystatus;
    }

    public function setAllTouristCompanyType($allTouristCompanyType) {
        $this->allTouristCompanyType[] = $allTouristCompanyType;
    }

    public function getTbtouristcompanyid() {
        return $this->tbtouristcompanyid;
    }

    public function getTbtouristcompanylegalname() {
        return $this->tbtouristcompanylegalname;
    }

    public function getTbtouristcompanymagicname() {
        return $this->tbtouristcompanymagicname;
    }

    public function getTbtouristcompanyowner() {
        return $this->tbtouristcompanyowner;
    }

    public function getTbtouristcompanycompanyType() {
        return $this->tbtouristcompanycompanyType;
    }

    public function getTbtouristcompanycustomcompanytype() {
        return $this->tbtouristcompanycustomcompanytype;
    }

    public function getTbtouristcompanyurl() {
        return $this->tbtouristcompanyurl;
    }

    public function getTbtouristcompanystatus() {
        return $this->tbtouristcompanystatus;
    }

    public function getAllTouristCompanyType() {
        return $this->allTouristCompanyType;

    }
/*     public function addPhoto(Photo $photo) {
        $this->photos[] = $photo;
    }

    public function getPhotos() {
        return $this->photos;
    } */
}