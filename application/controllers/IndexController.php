<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

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
        //$acl->allow('member', null, array('seeUsersList', 'addUser', 'deleteUser', 'lockUnlockUser'));

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
        $this->view->acl = $namespace->acl;        
//                Zend_Registry::getInstance()->set('aclData', $acl);
//                print_r(Zend_Registry::getInstance());
        $auth = Zend_Auth::getInstance();
//        print_r($auth);
        if ($auth->hasIdentity()) {
            $this->view->identity = $auth->getIdentity();
            if ($acl->isAllowed($auth->getIdentity()->role, null, 'seeUsersList')) {
                $mapper = new Application_Model_UserAccountMapper();
                $this->view->entries = $mapper->fetchAll();
            }
        }        
    }

    public function loginAction()
    {
        $form = new Application_Form_Logowanie();
        $form->setAction('')->setMethod('post');

        if ($this->_request->isPost()) {
//            if ($form->submit->isChecked()) {
//            $formValid = $form->isValid($this->_request->getPost());
            $form->isValid($this->_request->getPost());
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
        $form = new Application_Form_PrzypomnienieHasla();
        $form->setAction('')->setMethod('post');

        if ($this->_request->isPost()) {
//            if ($form->submit->isChecked()) {
            if ($form->isValid($this->_request->getPost())) {
                $username = $form->getValue('login');

                try {
                    $activationCode = $this->createActivationCode();

                    $mapper = new Application_Model_UserAccountMapper();
                    $userAccount = $mapper->findByLoginOrId($username);
                    $userAccount->getUserModel()->setHaslo($activationCode);
                    $mapper->save($userAccount);

                    $url = $this->view->url(
                            array(
                        'controller' => 'index',
                        'action' => 'activationlogin',
                        'login' => $username,
                        'Activation' => $activationCode
                            ), 'default', true);

                    $this->view->notifications = Application_Model_MailManagement::getInstance()->sendMail($userAccount->getUserModel()->getEmail(), Application_Model_MailManagement::INFO_TYPE_REMIND, $url);
                    $this->view->loginMessages = array('Mail z linkiem aktywacyjnym został wysłany');
                } catch (Exception $exc) {
                    $this->view->loginMessages = array($exc->getMessage());
                }
            }
//                    $this->_redirect('index/index');
//                } else {
////                    $this->view->errorMessage = $result->getMessages();
//                    $this->view->loginMessages = $result->getMessages();
//                    //$form->setMessage($result->getMessages());
//                }
//            }
            }
        $this->view->form = $form;

    }

    private function createActivationCode()
    {
        $activationKey =  mt_rand() . mt_rand() . mt_rand() . mt_rand() . mt_rand();
        return $activationKey;
    }

    public function changepasswordAction()
    {
        // action body
        $form = new Application_Form_ZmianaHasla();
        $form->setAction('')->setMethod('post');

        if ($this->_request->isPost()) {
//            if ($form->submit->isChecked()) {
            if ($form->isValid($this->_request->getPost())) {
                $auth = Zend_Auth::getInstance();

                $username = $auth->getIdentity();
                $password = $form->getValue('haslo');
                $mapper = new Application_Model_UserAccountMapper();

                try {
                    $userAccount = $mapper->findByLoginOrId($username);

                    $userAccount->getUserModel()->setHaslo($password);
                    $mapper->save($userAccount);

                    $this->view->loginMessages = array('Hasło zostało zmienione');
                } catch (Exception $exc) {
                    $this->view->loginMessages = array($exc->getMessage());
                }
            }
        }
        $this->view->form = $form;
    }

    public function activationloginAction()
    {
        // action body
        $username = $this->_request->getParam('login');
        $password = $this->_request->getParam('Activation');
        $authAdapter = new Application_Model_AuthUnit($username, $password);
        $auth = Zend_Auth::getInstance();

        try {
            $result = $auth->authenticate($authAdapter);
        } catch (Exception $e) {
            echo 'Caught exception: ', $e->getMessage(), "\n";
        }
        if ($result->isValid()) {
            try {
                $mapper = new Application_Model_UserAccountMapper();

                $userAccount = $mapper->findByLoginOrId($username);

                $userAccount->getUserModel()->setAktywne('tak');
                $mapper->save($userAccount);
                $this->_forward('remindpassword', 'index');
                
            } catch (Exception $exc) {
                    $this->echoE($exc);
            }
        } else {
            $messages = $result->getMessages();
            $messages['x'] = 'Niepoprawny link aktywacyjny. Skontaktuj się z administratorem';
            $this->view->loginMessages = $messages;
        }
        
    }

    public function lockunlockAction()
    {
        // action body
        $username = $this->_request->getParam('login');        
        try {
            $mapper = new Application_Model_UserAccountMapper();

            $userAccount = $mapper->findByLoginOrId($username);
            $aktywne = $userAccount->getUserModel()->getAktywne();
            $mysqldate = gmdate('Y-m-d H:i:s',time());            
            $ip = $this->getRequest()->getClientIp(TRUE); 
            if ($aktywne == 'tak') {
                $aktywne = 'nie';
                $userAccount->getAccountModel()->setData_blokady($mysqldate);
            } else {
                $aktywne = 'tak';
                $userAccount->getAccountModel()->setIp_odblokowania($ip);
                $userAccount->getAccountModel()->setData_odblokowania($mysqldate);
            }
            $userAccount->getUserModel()->setAktywne($aktywne);

            $mapper->save($userAccount);
            $this->_redirect('index/index');
        } catch (Exception $exc) {
            $this->echoE($exc);
        }
    }

    public function deleteuserAction()
    {
        // action body
        $username = $this->_request->getParam('login');
        try {
            $mapper = new Application_Model_UserAccountMapper();
            $mapper->deleteUser($username);
        } catch (Exception $exc) {
            $this->echoE($exc);
        }
    }

    private function echoE($exc){
            echo '<pre>';
            print_r($exc);
            echo '</pre>';
            echo '<pre>';
            echo $exc->getTraceAsString();
            echo '</pre>';
        
    }


}















