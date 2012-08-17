<?php

class Application_Model_UserAccount
{
    public function getUserModel() {
        return $this->userModel;
    }

    public function setUserModel($userModel) {
        $this->userModel = $userModel;
    }

    public function getAccountModel() {
        return $this->accountModel;
    }

    public function setAccountModel($accountModel) {
        $this->accountModel = $accountModel;
    }

    protected $userModel;
    protected $accountModel;
    
    public function __construct($userModel = null, $accountModel = null) {
        if ($this->userModel == NULL) {
            $this->userModel = new Application_Model_User();
        } else {
            $this->userModel = $userModel;
        }

        if ($this->accountModel == NULL) {
            $this->accountModel = Application_Model_Account();
        } else {
            $this->accountModel = $accountModel;
        }
    }

}

