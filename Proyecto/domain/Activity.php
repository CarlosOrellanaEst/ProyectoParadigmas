<?php

class Activity {
    private $idTBActivity;
    private $nameTBActivity;
    private $tbservicecompanyid;
    private $attributeTBActivityArray;
    private $dataAttributeTBActivityArray;
    private $tbactivityurl;
    private $statusTBActivity;
    private $activitydate;

    function __construct ($idTBActivity, $nameTBActivity,$tbservicecompanyid, $attributeTBActivityArray,$dataAttributeTBActivityArray, $tbactivityurl, $statusTBActivity=true, $activitydate) { 
        $this->idTBActivity = $idTBActivity;
        $this->tbservicecompanyid = $tbservicecompanyid;
        $this->nameTBActivity = $nameTBActivity;
        $this->attributeTBActivityArray = $attributeTBActivityArray;
        $this->dataAttributeTBActivityArray = $dataAttributeTBActivityArray;
        $this->tbactivityurl = $tbactivityurl;
        $this->statusTBActivity = $statusTBActivity;
        $this->activitydate = $activitydate;
    }

// Getters
public function getIdTBActivity() {
    return $this->idTBActivity;
}

public function getNameTBActivity() {
    return $this->nameTBActivity;
}

public function getTbservicecompanyid() {
    return $this->tbservicecompanyid;
}

public function getAttributeTBActivityArray() {
    return $this->attributeTBActivityArray;
}

public function getDataAttributeTBActivityArray() {
    return $this->dataAttributeTBActivityArray;
}

public function getTbactivityURL() {
    return $this->tbactivityurl;
}

public function getStatusTBActivity() {
    return $this->statusTBActivity;
}

public function getActivityDate() {
    return $this->activitydate;
}

// Setters
public function setIdTBActivity($idTBActivity) {
    $this->idTBActivity = $idTBActivity;
}

public function setNameTBActivity($nameTBActivity) {
    $this->nameTBActivity = $nameTBActivity;
}

public function setTbservicecompanyid($tbservicecompanyid) {
    $this->tbservicecompanyid = $tbservicecompanyid;
}

public function setAttributeTBActivityArray($attributeTBActivityArray) {
    $this->attributeTBActivityArray = $attributeTBActivityArray;
}

public function setDataAttributeTBActivityArray($dataAttributeTBActivityArray) {
    $this->dataAttributeTBActivityArray = $dataAttributeTBActivityArray;
}

public function setTbactivityURL($tbactivityurl) {
    $this->tbactivityurl = $tbactivityurl;
}

public function setStatusTBActivity($statusTBActivity) {
    $this->statusTBActivity = $statusTBActivity;
}

public function setActivityDate($activitydate) {
    $this->activitydate = $activitydate;

}

}

