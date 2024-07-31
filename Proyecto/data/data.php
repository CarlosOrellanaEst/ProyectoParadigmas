<?php

class Data {

    public $server;
    public $user;
    public $password;
    public $db;
    public $connection;
    public $isActive;

    /* constructor */

    public function __construct() {
        $hostName = gethostname();
        switch ($hostName) {
            case "loren": //Office's PC
                $this->isActive = false;
                $this->server = "127.0.0.1";
                $this->user = "root";
                $this->password = "1234";
                $this->db = "harvestmoon";
                break;
            case "admin": //laptop's PC
                $this->isActive = false;
                $this->server = "127.0.0.1";
                $this->user = "root";
                $this->password = "";
                $this->db = "harvestmoon";
                break;
            default:
                $this->isActive = false;
                $this->server = "127.0.0.1";
                $this->user = "root";
                $this->password = "";
                $this->db = "ecotouristiar";
                break;
        }
    }
}


