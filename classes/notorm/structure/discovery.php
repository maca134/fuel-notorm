<?php

namespace NotORM;

/** Structure reading meta-informations from the database
 */
class NotORM_Structure_Discovery implements NotORM_Structure {

    public static $_instances = array();

    public static function instance($name = null, array $config = null, $foreign = '%s') {
        \Config::load('notorm', true);
        if ($name === null) {
            // Use the default instance name
            $name = \Config::get('notorm.active');
        }
        if ($config === null) {
            // Load the configuration for this database
            $config = \Config::get("notorm.{$name}");
        }

        // Extract the connection parameters, adding required variabels
        $connection = array_merge(array(
            'dsn' => '',
            'username' => null,
            'password' => null,
            'persistent' => false,
                ), $config['connection']);

        $attrs = array(\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION);
        
        if (!empty($connection['persistent'])) {
            // Make the connection persistent
            $attrs[\PDO::ATTR_PERSISTENT] = true;
        }
        try {
            // Create a new PDO connection
            $pdo = new \PDO($connection['dsn'], $connection['username'], $connection['password'], $attrs);
        } catch (\PDOException $e) {
            $error_code = is_numeric($e->getCode()) ? $e->getCode() : 0;
            throw new \Database_Exception($e->getMessage(), $error_code, $e);
        }

        $cache = null;

        if (isset($config['enable_cache']) && $config['enable_cache'] == true) {
            $cache = new NotORM_Cache("notorm.{$name}", (isset($config['expiration'])) ? $config['expiration'] : null);
        }
        $instance = new static($pdo, $cache, $foreign);
        return $instance;
    }

    protected $connection, $cache, $structure = array();
    protected $foreign;

    /** Create autodisovery structure
     * @param PDO
     * @param NotORM_Cache
     * @param string use "%s_id" to access $name . "_id" column in $row->$name
     */
    function __construct(\PDO $connection, NotORM_Cache $cache = null, $foreign = '%s') {
        $this->connection = $connection;
        $this->cache = $cache;
        $this->foreign = $foreign;
        if ($cache) {
            $this->structure = $cache->load("structure");
        }
    }

    /** Save data to cache
     */
    function __destruct() {
        if ($this->cache) {
            $this->cache->save("structure", $this->structure);
        }
    }

    function getPrimary($table) {
        $return = &$this->structure["primary"][$table];
        if (!isset($return)) {
        foreach ($this->connection->query("EXPLAIN $table") as $column) {
                if ($column[3] == "PRI") { // 3 - "Key" is not compatible with PDO::CASE_LOWER
                    if ($return != "") {
                        $return = ""; // multi-column primary key is not supported
                        break;
                    }
                    $return = $column[0];
                }
            }
        }
        return $return;
    }

    function getReferencingColumn($name, $table) {
        $name = strtolower($name);
        $return = &$this->structure["referencing"][$table];
        if (!isset($return[$name])) {
            foreach ($this->connection->query("
				SELECT TABLE_NAME, COLUMN_NAME
				FROM information_schema.KEY_COLUMN_USAGE
				WHERE TABLE_SCHEMA = DATABASE()
				AND REFERENCED_TABLE_SCHEMA = DATABASE()
				AND REFERENCED_TABLE_NAME = " . $this->connection->quote($table) . "
				AND REFERENCED_COLUMN_NAME = " . $this->connection->quote($this->getPrimary($table)) //! may not reference primary key
            ) as $row) {
                $return[strtolower($row[0])] = $row[1];
            }
        }
        return $return[$name];
    }

    function getReferencingTable($name, $table) {
        return $name;
    }

    function getReferencedColumn($name, $table) {
        return sprintf($this->foreign, $name);
    }

    function getReferencedTable($name, $table) {
        $column = strtolower($this->getReferencedColumn($name, $table));
        $return = &$this->structure["referenced"][$table];
        if (!isset($return[$column])) {
            foreach ($this->connection->query("
				SELECT COLUMN_NAME, REFERENCED_TABLE_NAME
				FROM information_schema.KEY_COLUMN_USAGE
				WHERE TABLE_SCHEMA = DATABASE()
				AND REFERENCED_TABLE_SCHEMA = DATABASE()
				AND TABLE_NAME = " . $this->connection->quote($table) . "
			") as $row) {
                $return[strtolower($row[0])] = $row[1];
            }
        }
        return $return[$column];
    }

    function getSequence($table) {
        return null;
    }

}
