<?php

class Application_Form_Logowanie extends Zend_Form
{
    public function init()
    {
// Set the method for the display form to POST
        $this->setMethod('post');

        /* Form Elements & Other Definitions Here ... */
        $this->addElement('text', 'login', array(
            'label' => 'Login:',
            'required' => TRUE
            ,'filters'    => array('StringTrim')
            ,
            'validators' => array(
                array('validator' => 'StringLength', 'options' => array(4, 20),
                    'regex', false, array(
                        'pattern' => '/[a-zA-Z0-9_\-]/',
                        'messages' => 'Your first name cannot contain those characters : < >')
                )
            )
        ));

        $this->addElement('password', 'haslo', array(
            'label' => 'Hasło:',
            'required' => true
        ));
        
//        $view = Zend_Layout::getMvcInstance()->getView();  
//        $url = $view->url(array(  
//            'controller' => 'index', 'action' => 'remindPassword'  
//        ));  
//        $this->addElement($url, 'Przypomnij hasło');
        //$this->setAction($url);          

        $namespace = new Zend_Session_Namespace();
        if (isset($namespace->showCaptcha)) {
            
            $this->addElement('captcha', 'captcha', array(
                'label' => 'Please enter the 5 letters displayed below:',
                'required' => true,
                'filters'  => array('StringTrim'),                
                'captcha'  => array(
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

