<?php

include_once '../data/adminData.php';

class adminBusiness {

    private $adminData;

    public function __construct() {
        $this->adminData = new adminData();
    }
    public function insertTBUser($user) {
        return $this->adminData->insertTBUser($user);
    }
}