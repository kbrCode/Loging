<?php

class Application_Model_SpamIP
{
    
    public function getId() {
        return $this->id;
    }

    public function setSpamId($id) {
        $this->id = $id;
    }

    public function getData() {
        return $this->data;
    }

    public function setData($data) {
        $this->data = $data;
    }

    public function getIp() {
        return $this->ip;
    }

    public function setIp($ip) {
        $this->ip = $ip;
    }

        private $id;
    private $data;
    private $ip;

}

