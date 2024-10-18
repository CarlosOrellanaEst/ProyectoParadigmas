<?php

class Booking {
    private $idTBBooking;
    private $idTBActivity;
    private $idTBUser;
    private $numberPersonsTBBooking;
    private $statusTBBooking;
    private $bookingdate;

    private $confirmation;
    
    function __construct($idTBBooking, $idTBActivity, $idTBUser, $numberPersonsTBBooking, $statusTBBooking = true, $bookingdate, $confirmation) { 
        $this->idTBBooking = $idTBBooking;
        $this->idTBActivity = $idTBActivity;
        $this->idTBUser = $idTBUser;
        $this->numberPersonsTBBooking = $numberPersonsTBBooking;
        $this->statusTBBooking = $statusTBBooking;
        $this->bookingdate = $bookingdate;
        $this->confirmation = $confirmation;

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

    public function getBookingdate() {
        return $this->bookingdate;
    }

    public function getConfirmation() {
        return $this->confirmation;
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

    public function setBookingdate($bookingdate) {
        $this->bookingdate = $bookingdate;
    }

    public function setConfirmation($confirmation) {
        $this->confirmation = $confirmation;
    }


}
