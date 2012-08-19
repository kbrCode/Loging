<?php

class Application_Form_ZmianaHasla extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
        $this->setMethod('post');

        $this->addElement('password', 'haslo', array(
            'label' => 'Hasło:',
            'required' => true
        ));

        $this->addElement('password', 'hasloR', array(
            'label' => 'Powtórz hasło:',
            'required' => true,
            'validators' => array(
                array('identical', false, array('token' => 'haslo'))
            )
        ));
        
        // Add the submit button
        $this->addElement('submit', 'submit', array(
            'ignore' => true,
            'label' => 'Zmień',
        ));

        // And finally add some CSRF protection
        $this->addElement('hash', 'csrf', array(
            'ignore' => true,
        ));
        
    }

}

