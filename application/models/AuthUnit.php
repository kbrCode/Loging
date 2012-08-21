<?php

class Application_Model_AuthUnit implements Zend_Auth_Adapter_Interface
{
    private $login;
    private $password;
    private $captchaValid;

    public function __construct($login, $password, $captchaValid = TRUE)
    {
        $this->login = $login;
        $this->password = $password;
        $this->captchaValid = $captchaValid;
    }
    
    public function authenticate() {

        // pobieramy domyślny adapter bazy danych
        $db = Zend_Db_Table::getDefaultAdapter();
        
        // tworzymy instancję adaptera autoryzacji
        $authAdapter = new Zend_Auth_Adapter_DbTable($db, 'fx_user', 'login', 'haslo');
        // wprowadzamy dane do adaptera
        $authAdapter->setIdentity($this->login);
        $authAdapter->setCredential($this->password);
        
        //this one will be. Now removed for test purpose.
        //$authAdapter->setCredential(md5($data['haslo']));
        //setCredentialTreatment('md5(?) and system_id = 20')
        
// sprawdzamy, czy użytkownik jest aktywny
//Jeśli użytkownik się loguje z linku aktywacyjnego nie sprawdzamy czy konto jest aktywne.    
        // autoryzacja
        $result = $authAdapter->authenticate();

        $namespace = new Zend_Session_Namespace();
        if ($result->isValid() && $this->captchaValid) {
            $mapper = new Application_Model_UserAccountMapper();
            $userAccount = $mapper->findByLoginOrId($this->login);

            if ($userAccount->getUserModel()->getAktywne() != 'nie') {
                if (!$this->isTempInactive($userAccount)) {
                    if ($userAccount->getUserModel()->getAktywne() != 'tak') {
                        $userAccount->getUserModel()->setAktywne('tak');
                        $userAccount->getAccountModel()->setData_blokady(null);
                        $mysqldate = gmdate('Y-m-d H:i:s', time());
                        $userAccount->getAccountModel()->setData_odblokowania($mysqldate);
                        $mapper->save($userAccount);
                    }

                    $auth = Zend_Auth::getInstance();
                    $storage = $auth->getStorage();
                    // umieszczamy w sesji dane użytkownika
                    $storage->write($authAdapter->getResultRowObject(array(
                                'id', 'login', 'email', 'aktywne', 'role'
                            )));
                    //print_r($auth->getIdentity());
                    $this->removeFromNamespace();
                    return new Application_Model_LoginResult(Zend_Auth_Result::SUCCESS, $auth->getIdentity(), array());
                }
                return new Application_Model_LoginResult(Zend_Auth_Result::FAILURE, null, array("Konto zostało zablokowane do " . 
                    $userAccount->getAccountModel()->getData_blokady() .
                    " Proszę spróbować później."), 
                        Application_Model_LoginResult::FAILURE_ACCOUNT_LOCKED);
            } else {
                return new Application_Model_LoginResult(Zend_Auth_Result::FAILURE, null, 
                        array("Konto zostało zablokowane. Proszę skontaktować się z administratorem"), 
                        Application_Model_LoginResult::FAILURE_ACCOUNT_LOCKED);
            }
        } else if ($result->getCode() == Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND){
            //reset all if incorrect identity
            $this->removeFromNamespace();
            return new Application_Model_LoginResult(Zend_Auth_Result::FAILURE, null, array("Niepoprawny login"));
        }
        else if($result->getCode() == Zend_Auth_Result::SUCCESS && !$this->captchaValid)
        {
            if (isset($namespace->invalidCaptcha)) {
                if ($namespace->invalidCaptcha == 2) {
                    $resultArr = $this->unactivateAccount($this->login);
                    return new Application_Model_LoginResult(Zend_Auth_Result::FAILURE, null, $resultArr);
                } else {
                    $namespace->invalidCaptcha++;
                }
            } 
            else {
                $namespace->invalidCaptcha = 1;
            }
            return new Application_Model_LoginResult(Zend_Auth_Result::FAILURE, null, array("Niepoprawny captcha!"));            
        }
        else if ($result->getCode() == Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID) {
            $mapper = new Application_Model_UserAccountMapper();
            $userAccount = $mapper->findByLoginOrId($this->login);

            if ($userAccount->getUserModel()->getAktywne() == 'temp') {
                if (!$this->isTempInactive($userAccount)) {
                    return new Application_Model_LoginResult(Zend_Auth_Result::FAILURE, null, 
                            array("Konto zostało zablokowane. Proszę spróbować później"), 
                            Application_Model_LoginResult::FAILURE_ACCOUNT_LOCKED);
                } else {
                    $resultArr = $this->unactivateAccount($this->login);
                    return new Application_Model_LoginResult(Zend_Auth_Result::FAILURE, null, 
                            array("Konto zostało zablokowane. Proszę skontaktować się z administratorem"), 
                            Application_Model_LoginResult::FAILURE_ACCOUNT_LOCKED);
                }
            } else if ($userAccount->getUserModel()->getAktywne() == 'nie') {
                return new Application_Model_LoginResult(Zend_Auth_Result::FAILURE, null, 
                        array("Konto zostało zablokowane. Proszę skontaktować się z administratorem"), 
                        Application_Model_LoginResult::FAILURE_ACCOUNT_LOCKED);
            } else {
                if (isset($namespace->invalidLogins)) {
                    if ($namespace->invalidLogins == 3) {
                        $namespace->showCaptcha = TRUE;
                    } else {
                        $namespace->invalidLogins++;
                    }
                } else {
                    $namespace->invalidLogins = 1;
                }
                return new Application_Model_LoginResult(Zend_Auth_Result::FAILURE, null, array("Niepoprawny login lub hasło"));
            }
        }
    }
    private function removeFromNamespace() {
        $namespace = new Zend_Session_Namespace();
        unset($namespace->invalidLogins);
        unset($namespace->showCaptcha);
        unset($namespace->invalidCaptcha);
    }
    
    private function unactivateAccount($identity) {

        $unactivateCode = 0;
        $dateString = null;
        $mapper = new Application_Model_UserAccountMapper();
        $userAccount = $mapper->findByLoginOrId($identity);

        if ($userAccount->getUserModel()->getAktywne() == 'temp') {
            $dateString = gmdate('Y-m-d H:i:s', time());
            $active = 'nie';
            $unactivateCode = Application_Model_MailManagement::INFO_TYPE_LOCKED_NOTEND;
        } else {

            $date = new DateTime();
            $date->modify("+20 minutes");
            $active = 'temp';
            $unactivateCode = Application_Model_MailManagement::INFO_TYPE_LOCKED;
            $dateString = $date->format('Y-m-d H:i:s');
        }
        $userAccount->getUserModel()->setAktywne($active);        
        $userAccount->getAccountModel()->setData_blokady($dateString);        
        $mapper->save($userAccount);        
        
        $mail = $userAccount->getUserModel()->getEmail();

        //send mail to user .. locked
        $resultArr = Application_Model_MailManagement::getInstance()->SendMail($mail, $unactivateCode, $dateString);
        $this->removeFromNamespace();
        return $resultArr;
    }
    
    private function isTempInactive($identity){
        if (!$identity instanceof Application_Model_UserAccount) {
            $mapper = new Application_Model_UserAccountMapper();
            $identity = $mapper->findByLoginOrId($identity);
        }
        $date = $identity->getAccountModel()->getData_blokady();
        if($date == NULL){
            return FALSE;
        }
        return $this->isTempInactiveFromNow($date);
    }
    
    private function isTempInactiveFromNow($date) {
        $currdate = gmdate('Y-m-d H:i:s', time());
        $timediff = $this->date_diff($date, $currdate);
        if ($timediff['minutes_total'] <= 20) {
            return TRUE;
        }
        return FALSE;
    }

    private function date_diff($d1, $d2) {
        $d1 = (is_string($d1) ? strtotime($d1) : $d1);
        $d2 = (is_string($d2) ? strtotime($d2) : $d2);

        $diff_secs = abs($d1 - $d2);
        $base_year = min(date("Y", $d1), date("Y", $d2));

        $diff = mktime(0, 0, $diff_secs, 1, 1, $base_year);
        return array(
            "years" => date("Y", $diff) - $base_year,
            "months_total" => (date("Y", $diff) - $base_year) * 12 + date("n", $diff) - 1,
            "months" => date("n", $diff) - 1,
            "days_total" => floor($diff_secs / (3600 * 24)),
            "days" => date("j", $diff) - 1,
            "hours_total" => floor($diff_secs / 3600),
            "hours" => date("G", $diff),
            "minutes_total" => floor($diff_secs / 60),
            "minutes" => (int) date("i", $diff),
            "seconds_total" => $diff_secs,
            "seconds" => (int) date("s", $diff)
        );
    }

}

