<?php

include_once '../data/touristData.php';

class touristBusiness {

    private $touristata;

    public function __construct() {
        $this->touristata = new touristData();
    }
    public function insertTBUser($user) {
        return $this->touristata->insertTBUser($user);
    }
}