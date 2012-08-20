<?php

class Application_Model_UserAccount
{
    public function getUserModel() {
        return $this->userModel;
    }

    public function setUserModel($userModel) {
        if (!$userModel instanceof Application_Model_User) {
            throw new Exception('$userModel is not Application_Model_User');
        }
        $this->userModel = $userModel;
    }

    public function getAccountModel() {
        return $this->accountModel;
    }

    public function setAccountModel($accountModel) {
        if (!$accountModel instanceof Application_Model_Account) {
            throw new Exception('$userModel is not Application_Model_User');
        }
        $this->accountModel = $accountModel;
    }

    protected $userModel;
    protected $accountModel;
    
    public function __construct($userModel = null, $accountModel = null) {
        if ($userModel == NULL) {
            $this->userModel = new Application_Model_User();
        } else {
            $this->setUserModel($userModel);
        }
        if ($accountModel == NULL) {
            $this->accountModel = new Application_Model_Account();
        } else {
            $this->setAccountModel($accountModel);
        }
    }
}
    