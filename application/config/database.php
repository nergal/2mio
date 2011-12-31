<?php defined('SYSPATH') or die('No direct access allowed.');

return array
(
	'default' => array(
		'type'       => 'mysql',
		'connection' => array(
			'hostname'   => '127.0.0.1',
			'database'   => 'db',
			'username'   => 'xo4y-user',
			'password'   => 'p@s$W0rD',
			'persistent' => FALSE,
		),
		'table_prefix' => '',
		'charset'      => 'UTF8',
		'caching'      => TRUE,
		'profiling'    => TRUE,
	),
);
