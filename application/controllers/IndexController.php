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
                $captchaValid = TRUE;
                $pass = true;
        if (!$form->isValid($this->_request->getPost())){
            //detect which one is valid
            if($form->login->isValid($this->_request->getPost())){
                $pass = FALSE;
            }
            if($form->haslo->isValid($this->_request->getPost())){
                $pass = FALSE;
            }
            
            if (isset($form->captcha)) {
                    $captchaValid = $form->captcha->isValid($this->_request->getPost());
                }
            }
            if ($pass) {
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
                    $this->view->errorMessage = $result->getMessages();
                }
            }
//            }

             return $this->_redirect('/');
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
    }


}







