<?php

class Application_Model_Account extends Application_Model_DataBaseModel
{
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getFk_user_id() {
        return $this->fk_user_id;
    }

    public function setFk_user_id($fk_user_id) {
        $this->fk_user_id = $fk_user_id;
    }

    public function getFk_spam_ip_id() {
        return $this->fk_spam_ip_id;
    }

    public function setFk_spam_ip_id($fk_spam_ip_id) {
        $this->fk_spam_ip_id = $fk_spam_ip_id;
    }

    public function getData_blokady() {
        return $this->data_blokady;
    }

    public function setData_blokady($data_blokady) {
        $this->data_blokady = $data_blokady;
    }

    public function getData_odblokowania() {
        return $this->data_odblokowania;
    }

    public function setData_odblokowania($data_odblokowania) {
        $this->data_odblokowania = $data_odblokowania;
    }

    public function getIp_odblokowania() {
        return $this->ip_odblokowania;
    }

    public function setIp_odblokowania($ip_odblokowania) {
        $this->ip_odblokowania = $ip_odblokowania;
    }

    public function getKod_odblokowania() {
        return $this->kod_odblokowania;
    }

    public function setKod_odblokowania($kod_odblokowania) {
        $this->kod_odblokowania = $kod_odblokowania;
    }

    //fx_account
    protected $id;
    protected $fk_user_id;
    protected $fk_spam_ip_id;
    protected $data_blokady;
    protected $data_odblokowania;
    protected $ip_odblokowania;
    protected $kod_odblokowania;
}

