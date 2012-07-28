<?php
namespace NotORM;

// friend visibility emulation
abstract class NotORM_Abstract {

    protected $connection, $driver, $structure, $cache;
    protected $notORM, $table, $primary, $rows, $referenced = array();
    protected $debug = false;
    protected $freeze = false;
    protected $rowClass = 'NotORM_Row';

    protected function access($key, $delete = false) {
        
    }

}
