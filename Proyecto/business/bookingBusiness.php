<?php

include_once '../data/bookingData.php'; // Ajusta la ruta según tu estructura

class bookingBusiness {

    private $bookingData;

    public function __construct() {
        $this->bookingData = new bookingData();
    }
    public function insertTbBooking($booking) {
        return $this->bookingData->insertTbBooking($booking);

    }

    public function updateTbBooking($booking) {
        return $this->bookingData->updateTbBooking($booking);
    }

    public function deleteTbBooking($id) {
        return $this->bookingData->deleteTbBooking($id);
    }

    public function getAllTbBookings() {
        return $this->bookingData->getAllTbBookings();
    }

    public function getTbBookingById($id) {
        return $this->bookingData->getTbBookingById($id);
    }
    
}
    
