NotORM
======

This is still a working progress, its about 95% complete, most commenting everything and stuff up.

This is a wrapper package for [NotORM](http://www.notorm.com). The code has been slightly adjusted to be compatible with FuelPHP model generation.

Compared to the FuelPHP ORM package, its about twice as fast.

NotORM uses PHP PDO to connect to databases.

The package is also integrated into Fuel's Profiling.

Installation
------------

1.	Clone (`git clone git://github.com/maca134/fuel-notorm`) / [download](https://github.com/maca134/fuel-notorm/zipball/master)
2.	Copy to fuel/packages/
4.	Copy fuel/packages/notorm/config/notorm.php to fuel/app/config/
5.	Add database config.

# Introduction

##Insert example
	
	$notorm = NotORM::instance();
	$name = 'username';
	$password = 'password';
	$user = $notorm->users()->insert(array(
		'username' => $name,
		'password' => $password,
		'created_at' => time(),
		'updated_at' => time(),
	));

##Select Example
	
	$notorm = NotORM::instance();
	$users = $notorm->users()->limit($limit);
	foreach ($users as $user) {
		$messages = $user->messages();
		foreach ($messages as $message) {
			// do something
		}
	}

Checkout [NotORM](http://www.notorm.com) for more...