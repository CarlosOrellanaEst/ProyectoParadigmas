<?php

class ServiceCompany {
    private $idTbservicecompany;
    private $tbtouristcompanyid;
    private $tbserviceid;
    private $tbservicecompanyURL;
    private $tbservicetatus;

    function __construct($idTbservicecompany, $tbtouristcompanyid, $tbserviceid, $tbservicecompanyURL,$tbservicetatus) { 
        $this->idTbservicecompany = $idTbservicecompany;
        $this->tbtouristcompanyid = $tbtouristcompanyid;
        $this->tbserviceid = $tbserviceid;
        $this->tbservicecompanyURL = $tbservicecompanyURL;
        $this->tbservicetatus = $tbservicetatus;
    }

     // Getters
     public function getTbservicecompanyid() {
        return $this->idTbservicecompany;
    }

    public function getTbtouristcompanyid() {
        return $this->tbtouristcompanyid;
    }

    public function getTbserviceid() {
        return $this->tbserviceid;
    }

    public function getTbservicecompanyURL() {
        return $this->tbservicecompanyURL;
    }

    public function getTbservicetatus() {
        return $this->tbservicetatus;
    }

    // Setters
    public function setTbservicecompanyid($idTbservicecompany) {
        $this->idTbservicecompany = $idTbservicecompany;
    }

    public function setTbtouristcompanyid($tbtouristcompanyid) {
        $this->tbtouristcompanyid = $tbtouristcompanyid;
    }

    public function setTbserviceid($tbserviceid) {
        $this->tbserviceid = $tbserviceid;
    }

    public function setTbservicecompanyURL($tbservicecompanyURL) {
        $this->tbservicecompanyURL = $tbservicecompanyURL;
    }

    public function setTbservicetatus($tbservicetatus) {
        $this->tbservicetatus = $tbservicetatus;
    }

}