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
    
}
    
