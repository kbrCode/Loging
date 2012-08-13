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
    }

    public function loginAction() {
        $form = new Application_Form_Logowanie();
        $form->setAction('')->setMethod('post');

        if ($this->_request->isPost()) {
            if ($form->submit->isChecked()) {
                if ($form->isValid($_POST)) {
                    // pobieramy dane z formularza
                    $data = $form->getValues();
                    // pobieramy domyślny adapter bazy danych
                    $db = Zend_Db_Table::getDefaultAdapter();
                    // tworzymy instancję adaptera autoryzacji
                    $authAdapter = new Zend_Auth_Adapter_DbTable($db, 'fx_user', 'login', 'haslo', 'email');
                    // wprowadzamy dane do adaptera
                    $authAdapter->setIdentity($data['login']);
                    //$authAdapter->setCredential();
                    //this one will be. Now removed for test purpose.
                    //$authAdapter->setCredential(md5($data['haslo']));
                    $authAdapter->setCredential($data['haslo']);
//            // sprawdzamy, czy użytkownik jest aktywny
                    $authAdapter->setCredentialTreatment("? AND aktywne = 'tak'");
                    // autoryzacja
                    $result = $authAdapter->authenticate();
                    $email = 'abcde@gmail.com';
                    if ($result->isValid()) {
                        // umieszczamy w sesji dane użytkownika
                        $auth = Zend_Auth::getInstance();
                        $storage = $auth->getStorage();
                        $storage->write($authAdapter->getResultRowObject(array(
                                    'id', 'login', $email
                                )));
                        print_r($auth->getIdentity());

                        return $this->_redirect('/');
                    } else {
                        $namespace = new Zend_Session_Namespace();
                        if (isset($namespace->invalidLogins)) {
                            $namespace->invalidLogins++;
                        } else {
                            $namespace->invalidLogins = 1;
                        }
                        $this->view->loginMessage = "Niepoprawny login lub hasło";
                    }
                } else {
                    if (!$form->captcha->isValid($this->_request->getPost())) {
                        $namespace = new Zend_Session_Namespace();
                        if (isset($namespace->invalidCaptcha)) {
                            if($namespace->invalidCaptcha < 3){
                            $namespace->invalidCaptcha++;
                            }else {
//captcha is invalid send mail
                                }
                            }
                        } else {
                            $namespace->invalidCaptcha = 1;
                        }
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
}





