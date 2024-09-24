<?php

class Booking {
    private $idTBBooking;
    private $idTBActivity;
    private $idTBUser;
    private $numberPersonsTBBooking;
    private $statusTBBooking;
    

    function __construct ($idTBBooking, $idTBActivity, $idTBUser,$numberPersonsTBBooking, $statusTBBooking=true) { 
        $this->idTBBooking = $idTBBooking;
        $this->idTBActivity = $idTBActivity;
        $this->idTBUser = $idTBUser;
        $this->numberPersonsTBBooking = $numberPersonsTBBooking;
        $this->statusTBBooking = $statusTBBooking;
    }

// Getters
public function getIdTBBooking() {
    return $this->idTBBooking;
}

public function getIdTBActivity() {
    return $this->idTBActivity;
}

public function getIdTBUser() {
    return $this->idTBUser;
}

public function getNumberPersonsTBBooking() {
    return $this->numberPersonsTBBooking;
}

public function getStatusTBBooking() {
    return $this->statusTBBooking;
}

// Setters
public function setIdTBBooking($idTBBooking) {
    $this->idTBBooking = $idTBBooking;
}

public function setIdTBActivity($idTBActivity) {
    $this->idTBActivity = $idTBActivity;
}

public function setIdTBUser($idTBUser) {
    $this->idTBUser = $idTBUser;
}

public function setNumberPersonsTBBooking($numberPersonsTBBooking) {
    $this->numberPersonsTBBooking = $numberPersonsTBBooking;
}

public function setStatusTBBooking($statusTBBooking) {
    $this->statusTBBooking = $statusTBBooking;
}

}

