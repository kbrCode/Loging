<?php

class Application_Model_UserAccountMapper
{
    protected $_dbUserTable;
    protected $_dbAccountTable;
 
    public function setDbTable($dbUserTable, $dbAccountTable)
    {
        if (is_string($dbUserTable)) {
            $dbUserTable = new $dbUserTable();
        }
        if (!$dbUserTable instanceof Zend_Db_Table_Abstract) {
            throw new Exception('Invalid user table data gateway provided');
        }
        $this->_dbUserTable = $dbUserTable;
        
        if (is_string($dbAccountTable)) {
            $dbAccountTable = new $dbAccountTable();
        }
        if (!$dbAccountTable instanceof Zend_Db_Table_Abstract) {
            throw new Exception('Invalid account table data gateway provided');
        }
        $this->_dbAccountTable = $dbAccountTable;
        
        return $this;
    }
 
    public function getDbUserTable()
    {
        if (null === $this->_dbUserTable) {
            $this->setDbTable('Application_Model_DbTable_User');
        }
        return $this->_dbUserTable;
    }
    
    public function getDbAccountTable()
    {
        if (null === $this->_dbAccountTable) {
            $this->setDbTable('Application_Model_DbTable_User');
        }
        return $this->_dbAccountTable;
    }

    public function save(Application_Model_UserAccount $userAccount)
    {
        $userData = $userAccount->getUserModel()->toArray();
        $userAccount = $userAccount->getAccountModel()->toArray();
 
        try {
            $this->getDbUserTable()->beginTransaction();

            insertUpdateTable($this->getDbUserTable(), $userData);
            insertUpdateTable($this->getDbAccountTable(), $userAccount);            
            
            $this->_dbUserTable->getAdapter()->commit();
            
        } catch (Zend_Db_Exception $e) {
            $this->getDbUserTable()->getAdapter()->rollBack();
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    private function insertUpdateTable($table, $data) {

        if (null === ($id = $data['id'])) {
            unset($data['id']);
            $table->getAdapter()->insert($data);
        } else {
            $table->getAdapter()->update($data, array('id = ?' => $id));
        }
    }

    public function find($id, Application_Model_UserAccount $userAccount)
    {
//SELECT u1.*, a1.* FROM fx_user as u1
//left join fx_account as a1 on u1.id=a1.fk_user_id        
        
        $select = false;
        $sql = $this->getDbUserTable()->getAdapter()
                ->select()
                ->from(array('u' => 'fx_user'))
                ->joinLeft(array('a' => 'fx_account'), 'u.id=a.fk_user_id');

        if ($userAccount->getUserModel()->getId() != NULL) {

            $sql->where('u.id=?', $userAccount->getUserModel()->getId());
        } else {
            $sql->where('u.login=?', $userAccount->getUserModel()->getLogin());
        }

        $select = $this->bazaArtykuly->getAdapter()->fetchRow($sql);

        return $select;
    }
 
    public function fetchAll()
    {
        $resultSet = $this->getDbTable()->fetchAll();
        $entries   = array();
        foreach ($resultSet as $row) {
            $entry = new Application_Model_UserAccount();
            $entry->setId($row->id)
                  ->setEmail($row->email)
                  ->setComment($row->comment)
                  ->setCreated($row->created);
            $entries[] = $entry;
        }
        return $entries;
    }

}

