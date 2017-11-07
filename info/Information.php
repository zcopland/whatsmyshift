<?php

class Information {
    protected $host = 'smtp.gmail.com';
    protected $Username = 'whatsmyshift@gmail.com';
    protected $Password = 'wyqRCAv+8Hkg!7D_';
    protected $AccountSid = 'AC3838f81fcd3dbc7f24fb25695b4137b9';
    protected $AuthToken = '98a69e68f2c351d86fd0a3e7c260abbc';
    
    public function getHost() {
        return $this->host;
    }
    public function getUsername() {
        return $this->Username;
    }
    public function getPassword() {
        return $this->Password;
    }
    public function getAccountSid() {
        return $this->AccountSid;
    }
    public function getAuthToken() {
        return $this->AuthToken;
    }
}

  
?>