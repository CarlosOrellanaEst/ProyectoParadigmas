<?php

class Data {

    public $server;
    public $user;
    public $password;
    public $db;
    public $connection;
    public $isActive;

    /* constructor */

    public function Data() {
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
            default: //Hosting
                 $this->isActive = false;
      			 $this->server = "x.x.x.x";
      			 $this->user = "xxxxxxx";
      			 $this->password = "xxxxxxx";
      			 $this->db = "xxxxxxxxxx"; 
                break;
        }
    }

}
