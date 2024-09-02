<?php
class touristCompany{

    private $tbtouristcompanyid;
    private $tbtouristcompanylegalname;
    private $tbtouristcompanymagicname;
    private $tbtouristcompanyowner;
    private $tbtouristcompanycompanyType;
    private $tbphotoid;
    private $tbtouristcompanystatus;
    private $photos; 

    public function __construct($tbtouristcompanyid, $tbtouristcompanylegalname, $tbtouristcompanymagicname, $tbtouristcompanyowner, $tbtouristcompanycompanyType, $tbphotoid, $tbtouristcompanystatus){
        $this->tbtouristcompanyid = $tbtouristcompanyid;
        $this->tbtouristcompanylegalname = $tbtouristcompanylegalname;
        $this->tbtouristcompanymagicname = $tbtouristcompanymagicname;
        $this->tbtouristcompanyowner = $tbtouristcompanyowner;
        $this->tbtouristcompanycompanyType = $tbtouristcompanycompanyType;
        $this->tbphotoid = $tbphotoid;
        $this->tbtouristcompanystatus = $tbtouristcompanystatus;
        $this->photos = array();
    }

    
    // Setters
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

    public function setTbphotoid($tbphotoid) {
        $this->tbphotoid = $tbphotoid;
    }

    public function setTbtouristcompanystatus($tbtouristcompanystatus) {
        $this->tbtouristcompanystatus = $tbtouristcompanystatus;
    }

    // Getters
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

    public function getTbphotoid() {
        return $this->tbphotoid;
    }

    public function getTbtouristcompanystatus() {
        return $this->tbtouristcompanystatus;
    }
    public function addPhoto(Photo $photo) {
        $this->photos[] = $photo;
    }

    public function getPhotos() {
        return $this->photos;
    }
}