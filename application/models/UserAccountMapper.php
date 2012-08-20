<?php

class Application_Model_UserAccountMapper
{
    protected $_dbUserTable;
    protected $_dbAccountTable;
 
    public function setDbTable($dbTable)
    {
        if (is_string($dbTable)) {
            $dbTable = new $dbTable();
        }
        if (!$dbTable instanceof Zend_Db_Table_Abstract) {
            throw new Exception('Invalid user table data gateway provided');
        }
        return $dbTable;
    }
 
    public function getDbUserTable()
    {
        if (null === $this->_dbUserTable) {
            $this->_dbUserTable = $this->setDbTable('Application_Model_DbTable_User');
        }
        return $this->_dbUserTable;
    }
    
    public function getDbAccountTable()
    {
        if (null === $this->_dbAccountTable) {
            $this->_dbAccountTable = $this->setDbTable('Application_Model_DbTable_Account');
        }
        return $this->_dbAccountTable;
    }

    public function save(Application_Model_UserAccount $userAccount)
    {
        $userData = $userAccount->getUserModel()->toArray();

        try {
            $this->getDbUserTable()->getAdapter()->beginTransaction();

            $id = $this->insertUpdateTable($this->getDbUserTable(), $userData);
            $userAccount->getAccountModel()->setFk_user_id($id);
            $account = $userAccount->getAccountModel()->toArray();            
            $this->insertUpdateTable($this->getDbAccountTable(), $account);            
            
            $this->_dbUserTable->getAdapter()->commit();
            
        } catch (Zend_Db_Exception $e) {
            $this->getDbUserTable()->getAdapter()->rollBack();
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    private function insertUpdateTable($table, $data) {

        if (null === ($id = $data['id'])) {
            unset($data['id']);
            $table->insert($data);
            $id = $table->getAdapter()->lastInsertId();            
        } else {
            $table->update($data, array('id = ?' => $id));
        }
        return $id;        

    }

    public function find(Application_Model_UserAccount $userAccount)
    {
//SELECT u1.*, a1.* FROM fx_user as u1
//left join fx_account as a1 on u1.id=a1.fk_user_id        
        $sql = $this->selectBothTables();
        if ($userAccount->getUserModel()->getId() != NULL) {

            $sql->where('u.id=?', $userAccount->getUserModel()->getId());
        } else {
            $sql->where('u.login=?', $userAccount->getUserModel()->getLogin());
        }

        $select = $this->getDbAccountTable()->getAdapter()->fetchRow($sql);
        $userAccount->setUserModel(new Application_Model_User($select));
        $userAccount->setAccountModel(new Application_Model_Account($select));

        return $select;
    }
    
    public function findByLoginOrId($val)
    {
//SELECT u1.*, a1.* FROM fx_user as u1
//left join fx_account as a1 on u1.id=a1.fk_user_id        
//        $sql = $this->getDbUserTable()->getAdapter()
//                ->select()
//                ->from(array('u' => 'fx_user'))
//                ->joinLeft(array('a' => 'fx_account'), 'u.id=a.fk_user_id');

        $sql = $this->selectBothTables();
        if (is_int($val)) {

            $sql->where('u.id=?', $val);
        } else {
            $sql->where('u.login=?', $val);
        }

        $select = $this->getDbAccountTable()->getAdapter()->fetchRow($sql);
        if ($select == 0) {
            throw new Exception('Nie ma takiego użytkownika ' . $val);
        }
        $userAccount = new Application_Model_UserAccount(
                new Application_Model_User($select), 
                new Application_Model_Account($select));

        return $userAccount;
    }
    
    private function selectBothTables(){
//SELECT u.id as userId,a.id as accountId, u.login, u.haslo, u.email, 
//                    u.aktywne, u.role, a.fk_user_id, 
//                    a.fk_spam_ip_id, a.data_blokady, a.data_odblokowania, a.ip_odblokowania FROM fx_user as u
//left join fx_account as a on u.id=a.fk_user_id        
        
        $sql = $this->getDbUserTable()->getAdapter()
                ->select()
                ->from(array('u' => 'fx_user'), array('userId' => 'u.id', 'accountId' => 'a.id', 
                    'u.login', 'u.haslo', 'u.email', 
                    'u.aktywne', 'u.role', 'a.fk_user_id', 
                    'a.fk_spam_ip_id', 'a.data_blokady', 'a.data_odblokowania', 'a.ip_odblokowania'))
                ->joinLeft(array('a' => 'fx_account'), 'u.id=a.fk_user_id');
        return $sql;
    }

        public function fetchAll()
    {
        $sql = $this->selectBothTables();
        $resultSet = $this->getDbAccountTable()->getAdapter()->fetchAll($sql);

        $entries = array();
        foreach ($resultSet as $row) {
            $entries[] = new Application_Model_UserAccount(new Application_Model_User($row), new Application_Model_Account($row));
        }
        return $entries;
    }

    public function deleteUser($login) {
//$where = $table->getAdapter()->quoteInto('bug_id = ?', 1235);
//$table->delete($where);        
        $this->getDbUserTable()->delete(Array("login = ?" => $login));
    }

}

