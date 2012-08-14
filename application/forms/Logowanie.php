<?php

class Application_Form_Logowanie extends Zend_Form
{
    public function init()
    {
// Set the method for the display form to POST
        $this->setMethod('post');

        /* Form Elements & Other Definitions Here ... */
        $this->addElement('textarea', 'login', array(
            'label' => 'Login:',
            'required' => true,
            'validators' => array(
                array('validator' => 'StringLength', 'options' => array(4, 20),
                    'regex', false, array(
                        'pattern' => '/[a-zA-Z0-9_\-]/',
                        'messages' => 'Your first name cannot contain those characters : < >')
                )
            )
        ));

        $this->addElement('password', 'haslo', array(
            'label' => 'Password:',
            'required' => true
        ));

        $namespace = new Zend_Session_Namespace();
        if (isset($namespace->invalidLogins) && $namespace->invalidLogins == 4) {
            
            $this->addElement('captcha', 'captcha', array(
                'label' => 'Please enter the 5 letters displayed below:',
                'required' => true,
                'captcha' => array(
                    'captcha' => 'Figlet',
                    'wordLen' => 5,
                    'timeout' => 300
                )
            ));
        }
        // Add the submit button
        $this->addElement('submit', 'submit', array(
            'ignore' => true,
            'label' => 'Login',
        ));

        // And finally add some CSRF protection
        $this->addElement('hash', 'csrf', array(
            'ignore' => true,
        ));
    }

}

