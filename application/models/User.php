<?php

class Application_Model_User extends Application_Model_DataBaseModel
{
    public function getUserId() {
        return $this->getId();
    }

    public function setUserId($id) {
        $this->setId($id);
    }
    
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
    
    public function getRole() {
        return $this->role;
    }

    public function setRole($role) {
        $this->role = $role;
    }

    //fx_user
    protected $id;
    protected $login;
    protected $haslo;
    protected $email;
    protected $aktywne;    
    protected $role;
}

