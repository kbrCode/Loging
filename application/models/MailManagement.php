<?php

class Application_Model_MailManagement
{
    const INFO_TYPE_LOCKED = 1;
    const INFO_TYPE_LOCKED_NOTEND = 2;    
    const INFO_TYPE_REMIND = 3;
    
    private static $instance = false;

        public static function getInstance() {
        if (self::$instance == FALSE) {
            self::$instance = new Application_Model_MailManagement();
        }
        return self::$instance;
    }

    private function __construct(){}


public function SendMail($recipient, $code, $additionalParam) {

        $code = (int) $code;

        switch ($code) {
            case self::INFO_TYPE_LOCKED: {
                    $subject = 'Logowanie - problem z kontem';
                    $bodyText = 'Drogi użytkowniku, Twoje konto zostało zablokowane na 20 minut do ' . $additionalParam;
                }
                break;
            case self::INFO_TYPE_REMIND: {
                    $subject = 'Logowanie - przypomnienie hasła';
                    $bodyText = 'Drogi użytkowniku <a href="' . $additionalParam . '/">kliknij w link aktywacyjny</a>';
                }
                break;
                case self::INFO_TYPE_LOCKED_NOTEND: {
                    $subject = 'Logowanie - problem z kontem';
                    $bodyText = 'Drogi użytkowniku, Twoje konto zostało zablokowane. Skontaktuj się proszę z admiistratorem';
                }
        }

        $tr = new Zend_Mail_Transport_Smtp('mail.example.com', array(
                    'auth' => 'login',
                    'username' => 'kbrcode',
                    'password' => 'Mailpassword',
                    'port' => '8090'));

        Zend_Mail::setDefaultTransport($tr);


        $from = 'logowanie@logowanie.pl ' . ' Logowanie';
        $to = $recipient;
        $mail = new Zend_Mail();
        $mail->setBodyHtml($bodyText);
        $mail->setFrom($from, 'Logowanie');
        $mail->addTo($to, 'John Smith');
        $mail->setSubject($subject);

        try {
            $mail->send();
        } catch (Zend_Mail_Exception $e) {
            // Do something here, mail failed to send
            echo '<pre>';
            print_r($e);
            echo '</pre>';
        }
        return array($from, $to, $subject, $bodyText);
    }

}

