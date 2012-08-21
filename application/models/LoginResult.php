<?php

class Application_Model_LoginResult extends Zend_Auth_Result
{
    const ADDITIONAL_CODE_NONE = 0;
    const FAILURE_ACCOUNT_LOCKED = -5;

    public function getAdditionalCode() {
        return $this->additionalCode;
    }

    public function setAdditionalCode($additionalCode) {
        $this->additionalCode = $additionalCode;
    }

    private $additionalCode;

    public function __construct($code, $identity, array $messages = array(), $additionalCode = self::ADDITIONAL_CODE_NONE) {
        parent::__construct($code, $identity, $messages);
        $this->setAdditionalCode($additionalCode);
    }

}

