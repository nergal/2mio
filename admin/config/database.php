<?php defined('SYSPATH') or die('No direct access allowed.');

return array
(
	'default' => array(
		'type'       => 'mysql',
		'connection' => array(
			'hostname'   => '127.0.0.1',
			'database'   => 'db',
			'username'   => 'root',
			'password'   => 'p@s$W0rD',
			'persistent' => FALSE,
		),
		'table_prefix' => NULL,
		'charset'      => 'UTF8',
		'caching'      => FALSE,
		'profiling'    => TRUE,
	),
);