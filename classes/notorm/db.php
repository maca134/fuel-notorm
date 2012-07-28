<?php

namespace NotORM;

class NotORM_Db extends NotORM_Abstract {

    public $_config = array();

    public function __construct(\PDO $connection, NotORM_Structure $structure = null, NotORM_Cache $cache = null, array $config = array()) {
        $this->_config = $config;
        $this->connection = $connection;
        $this->driver = $connection->getAttribute(\PDO::ATTR_DRIVER_NAME);
        if (!isset($structure)) {
            $structure = new NotORM_Structure_Convention;
        }
        $this->structure = $structure;
        $this->cache = $cache;
    }

    /** Get table data to use as $db->table[1]
     * @param string
     * @return NotORM_Result
     */
    public function __get($table) {
        return new NotORM_Result($this->structure->getReferencingTable($table, ''), $this, true);
    }

    /** Set write-only properties
     * @return null
     */
    public function __set($name, $value) {
        if ($name == "debug" || $name == "freeze" || $name == "rowClass") {
            $this->$name = $value;
        }
        if ($name == "transaction") {
            switch (strtoupper($value)) {
                case "BEGIN": return $this->connection->beginTransaction();
                case "COMMIT": return $this->connection->commit();
                case "ROLLBACK": return $this->connection->rollback();
            }
        }
    }

    /** Get table data
     * @param string
     * @param array (["condition"[, array("value")]]) passed to NotORM_Result::where()
     * @return NotORM_Result
     */
    public function __call($table, array $where) {
        $return = new NotORM_Result($this->structure->getReferencingTable($table, ''), $this);
        if ($where) {
            call_user_func_array(array($return, 'where'), $where);
        }
        return $return;
    }

}

