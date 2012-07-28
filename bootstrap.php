<?php

/**
 * A package to use Twilio api https://www.twilio.com.
 *
 * @package    NotORM
 * @version    0.1
 * @author     Matthew McConnell
 * @license    MIT License
 * @copyright  2012 Matthew McConnell
 * @link       http://maca134.co.uk
 */
Autoloader::add_core_namespace('NotORM');

Autoloader::add_classes(array(
    'NotORM\\NotORM'                        => __DIR__ . '/classes/notorm.php',
    'NotORM\\NotORM_Abstract'               => __DIR__ . '/classes/notorm/abstract.php',
    'NotORM\\NotORM_cache'                  => __DIR__ . '/classes/notorm/cache.php',
    'NotORM\\NotORM_Db'                     => __DIR__ . '/classes/notorm/db.php',
    'NotORM\\NotORM_Literal'                => __DIR__ . '/classes/notorm/literal.php',
    'NotORM\\NotORM_MultiResult'            => __DIR__ . '/classes/notorm/multiresult.php',
    'NotORM\\NotORM_Result'                 => __DIR__ . '/classes/notorm/result.php',
    'NotORM\\NotORM_Row'                    => __DIR__ . '/classes/notorm/row.php',
    'NotORM\\NotORM_Structure'              => __DIR__ . '/classes/notorm/structure.php',
    'NotORM\\NotORM_Structure_Convention'   => __DIR__ . '/classes/notorm/structure/convention.php',
    'NotORM\\NotORM_Structure_Discovery'    => __DIR__ . '/classes/notorm/structure/discovery.php'
));
