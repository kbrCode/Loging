<?php

class Application_Model_AuthUnit implements Zend_Auth_Adapter_Interface
{
    private $login;
    private $password;
    private $captchaValid;
    private $email;


    public function __construct($login, $password, $captchaValid)
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
        //$authAdapter->setCredential();
        //this one will be. Now removed for test purpose.
        //$authAdapter->setCredential(md5($data['haslo']));
        $authAdapter->setCredential($this->password);
//            // sprawdzamy, czy użytkownik jest aktywny
        $authAdapter->setCredentialTreatment("? AND aktywne = 'tak'");
        // autoryzacja
        $result = $authAdapter->authenticate();
        $this->email = 'abcde@gmail.com';
        $namespace = new Zend_Session_Namespace();        
        if ($this->captchaValid && $result->isValid()) {
            // umieszczamy w sesji dane użytkownika
            $auth = Zend_Auth::getInstance();
            $storage = $auth->getStorage();
            $storage->write($authAdapter->getResultRowObject(array(
                        'id', 'login', 'email', 'aktywne'
                    )));
            //print_r($auth->getIdentity());
            unset($namespace->invalidLogins);
            unset($namespace->showCaptcha);
            unset($namespace->invalidCaptcha);
            return new Zend_Auth_Result(Zend_Auth_Result::SUCCESS, $auth->getIdentity(), array());
        } 
        else if(!$this->captchaValid)
        {
            if (isset($namespace->invalidCaptcha)) {
                if ($namespace->invalidCaptcha == 2) {
                    //send mail to user .. locked for 20 minutes
                    if ($result->getCode() != Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND) {

                        $tr = new Zend_Mail_Transport_Smtp('mail.example.com', array(
                                    'auth' => 'login',
                                    'username' => 'kbrcode',
                                    'password' => 'Mailpassword',
                                    'port' => '8090'));

                        Zend_Mail::setDefaultTransport($tr);
                        $subject = 'Logowanie - problem z kontem';
                        $bodyText = 'Drogi użytkowniku, Twoje konto zostało zablokowane na 20 minut';
                        $from = 'logowanie@logowanie.pl ' . ' Logowanie';
                        $to = 'john@example.com';
                        $mail = new Zend_Mail();
                        $mail->setBodyText($bodyText);
                        $mail->setFrom($from, 'Logowanie');
                        $mail->addTo($to, 'John Smith');
                        $mail->setSubject($subject);
                        $mail->send();
                    }
                    return new Zend_Auth_Result(Zend_Auth_Result::FAILURE, null, array("Niepoprawny captcha! Konto zostało zablokowane !",
                        $from, $to, $subject, $bodyText));
                } 
                else {
                    $namespace->invalidCaptcha++;
                }
            } 
            else {
                $namespace->invalidCaptcha = 1;
            }
            return new Zend_Auth_Result(Zend_Auth_Result::FAILURE, null, array("Niepoprawny captcha!"));            
        }
        else {

            if (isset($namespace->invalidLogins)) {
                if ($namespace->invalidLogins == 3) {
                    $namespace->showCaptcha = TRUE;
                } 
                else {
                    $namespace->invalidLogins++;
                }
            } 
            else {
                $namespace->invalidLogins = 1;
            }
            return new Zend_Auth_Result(Zend_Auth_Result::FAILURE, null, array("Niepoprawny login lub hasło"));            
        }
    }

}

