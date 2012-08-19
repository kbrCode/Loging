<?php

class Application_Form_PrzypomnienieHasla extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
        $this->setMethod('post');

        /* Form Elements & Other Definitions Here ... */
        $this->addElement('text', 'login', array(
            'label' => 'Login:',
            'required' => TRUE
            , 'filters' => array('StringTrim')
            ,
            'validators' => array(
                array('validator' => 'StringLength', 'options' => array(4, 20),
                    'regex', false, array(
                        'pattern' => '/[a-zA-Z0-9_\-]/',
                        'messages' => 'Your first name cannot contain those characters : < >')
                )
            )
        ));

        $this->addElement('captcha', 'captcha', array(
            'label' => 'Please enter the 5 letters displayed below:',
            'required' => true,
            'filters' => array('StringTrim'),
            'captcha' => array(
                'captcha' => 'Figlet',
                'wordLen' => 5,
                'timeout' => 300
            )
        ));

        // Add the submit button
        $this->addElement('submit', 'submit', array(
            'ignore' => true,
            'label' => 'Przypomnij',
        ));

        // And finally add some CSRF protection
        $this->addElement('hash', 'csrf', array(
            'ignore' => true,
        ));
    }


}

