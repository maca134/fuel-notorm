<?php

return array(
    'active' => 'default',
    /**
     * Base config, just need to set the DSN, username and password in env. config.
     */
    'default' => array(
        'connection' => array(
            'dsn' => 'mysql:host=localhost;dbname=',
            'username' => 'root',
            'password' => 'root',
            'persistent' => false,
        ),
        'enable_cache' => false,
        // default expiration (null = no expiration)
	'expiration'  => null,
        'profiling' => true
    ),
);