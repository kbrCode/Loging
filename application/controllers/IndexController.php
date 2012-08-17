<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }
//http://www.brucerick.com/changing-wamp-2-1-port-from-80-to-81-or-other-port/
    public function indexAction()
    {
        $acl = new Zend_Acl();

// Add groups to the Role registry using Zend_Acl_Role
// Guest does not inherit access controls
        $roleGuest = new Zend_Acl_Role('guest');
        $admin = new Zend_Acl_Role('admin');

        $acl->addRole($roleGuest)
                ->addRole(new Zend_Acl_Role('member'))
                ->addRole(new Zend_Acl_Role($admin));

// Staff inherits view privilege from guest, but also needs additional
// privileges
        $acl->allow('guest', null, array('addGestbook'));

// Editor inherits view, edit, submit, and revise privileges from
// staff, but also needs additional privileges
        $acl->allow('member', null, array('addGestbook', 'deleteGestbook', 'editSeo'));

// Administrator inherits nothing, but is allowed all privileges
        $acl->allow('admin');

        $parents = array('guest', 'member', 'admin');
        $acl->addRole(new Zend_Acl_Role('someUser'), $parents);


// Staff inherits from guest
        $acl->addRole(new Zend_Acl_Role('staff'), $roleGuest);

        /*
          Alternatively, the above could be written:
          $acl->addRole(new Zend_Acl_Role('staff'), 'guest');
         */

// Editor inherits from staff
        $acl->addRole(new Zend_Acl_Role('editor'), 'staff');

// Administrator does not inherit access controls
        $acl->addRole(new Zend_Acl_Role('administrator'), $admin);

        $namespace = new Zend_Session_Namespace();
        $namespace->acl = $acl;
//                Zend_Registry::getInstance()->set('aclData', $acl);
//                print_r(Zend_Registry::getInstance());
        $auth = Zend_Auth::getInstance();
//        print_r($auth);
        if ($auth->hasIdentity()) {
            $this->view->identity = $auth->getIdentity();
        }        
    }

    public function loginAction()
    {
        $form = new Application_Form_Logowanie();
        $form->setAction('')->setMethod('post');

        if ($this->_request->isPost()) {
//            if ($form->submit->isChecked()) {
            $formValid = $form->isValid($this->_request->getPost());
                $captchaValid = TRUE;
                $pass = true;
                $data = $form->getValues();
                $post1 = $this->_request->getPost('captcha');
//                $post2 = $_POST;

            if(!$form->login->isValid($data['login'])){
                $pass = FALSE;
            }
            if(!$form->haslo->isValid($data['haslo'])){
                $pass = FALSE;
            }
            if ($pass == TRUE && isset($form->captcha)) {
                    $captchaValid = $form->captcha->isValid($post1, $this->_request->getPost());
                }
        if ($pass){
                    // pobieramy dane z formularza
            $username = $form->getValue('login');
            $password = $form->getValue('haslo');
                    
           $authAdapter = new Application_Model_AuthUnit($username, $password, $captchaValid);

            $auth = Zend_Auth::getInstance();

            try
            {
                $result = $auth->authenticate($authAdapter);
            }
            catch (Exception $e) {
                        echo 'Caught exception: ', $e->getMessage(), "\n";
                    }
                if ($result->isValid()) {
                    $this->_redirect('index/index');
                } else {
//                    $this->view->errorMessage = $result->getMessages();
                    $this->view->loginMessages = $result->getMessages();
                    //$form->setMessage($result->getMessages());
                }
            }
            }
        $this->view->form = $form;
    }

    public function logoutAction()
    {
        // action body
        $auth = Zend_Auth::getInstance();
        $auth->clearIdentity();
        return $this->_redirect('/');
    }

    public function remindpasswordAction()
    {
        // action body
        $activationKey = createActivationCode();
    }

    private function createActivationCode()
    {
        $activationKey =  mt_rand() . mt_rand() . mt_rand() . mt_rand() . mt_rand();
        return $activationKey;
    }

}







