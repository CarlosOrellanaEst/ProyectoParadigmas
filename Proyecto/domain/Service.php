<?php

class Service {
    private $idTbservice;
    private $tbservicename;
    private $tbservicedescription;
    private $tbservicetatus;

    // Constructor
    function __construct($idTbservice, $tbservicename, $tbservicedescription, $tbservicetatus) { 
        $this->idTbservice = $idTbservice;
        $this->tbservicename = $tbservicename;
        $this->tbservicedescription = $tbservicedescription;
        $this->tbservicetatus = $tbservicetatus;
    }

    // Getters and Setters

    // idTbservice
    public function getIdTbservice() {
        return $this->idTbservice;
    }

    public function setIdTbservice($idTbservice) {
        $this->idTbservice = $idTbservice;
    }

    // tbservicename
    public function getTbservicename() {
        return $this->tbservicename;
    }

    public function setTbservicename($tbservicename) {
        $this->tbservicename = $tbservicename;
    }

    // tbservicedescription
    public function getTbservicedescription() {
        return $this->tbservicedescription;
    }

    public function setTbservicedescription($tbservicedescription) {
        $this->tbservicedescription = $tbservicedescription;
    }

    // tbservicetatus
    public function getTbservicetatus() {
        return $this->tbservicetatus;
    }

    public function setTbservicetatus($tbservicetatus) {
        $this->tbservicetatus = $tbservicetatus;
    }
}
