<?php

namespace NotORM;

class NotORM {

    public static $_instances = array();

    public static function instance($name = null, array $config = null, NotORM_Structure $structure = null) {
        \Config::load('notorm', true);
        if ($name === null) {
            // Use the default instance name
            $name = \Config::get('notorm.active');
        }
        if (!isset(static::$_instances[$name])) {
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
            unset($config['connection']);
            $config['instance'] = $name;
            static::$_instances[$name] = new NotORM_Db($pdo, $structure, $cache, $config);
        }
        return static::$_instances[$name];
    }

}

