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
        if ($userModel == NULL) {
            $this->userModel = new Application_Model_User();
        } else {
            $this->userModel = $userModel;
        }

        if ($accountModel == NULL) {
            $this->accountModel = new Application_Model_Account();
        } else {
            $this->accountModel = $accountModel;
        }
    }

}

