<?php

class Application_Model_User extends DataBaseModel
{
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getLogin() {
        return $this->login;
    }

    public function setLogin($login) {
        $this->login = $login;
    }

    public function getHaslo() {
        return $this->haslo;
    }

    public function setHaslo($haslo) {
        $this->haslo = $haslo;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function getAktywne() {
        return $this->aktywne;
    }

    public function setAktywne($aktywne) {
        $this->aktywne = $aktywne;
    }

    //fx_user
    protected $id;
    protected $login;
    protected $haslo;
    protected $email;
    protected $aktywne;    
}

