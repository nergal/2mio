<?php defined('SYSPATH') or die('No direct script access.');
return array
(
	'memcache' => array
	(
		'driver'             => 'memcache',
		'default_expire'     => 0,
		'compression'        => FALSE,              // Use Zlib compression (can cause issues with integers)
		'servers'            => array
		(
			array
			(
				'host'             => 'localhost',  // Memcache Server
				'port'             => 11212,        // Memcache port number
				'persistent'       => FALSE,        // Persistent connection
				'weight'           => 1,
				'timeout'          => 1,
				'retry_interval'   => 15,
				'status'           => TRUE,
			),
		),
		'instant_death'      => TRUE,               // Take server offline immediately on first fail (no retry)
		'profiling'          => TRUE,
	),
	'apc'      => array
	(
		'driver'             => 'apc',
		'default_expire'     => 3600,
	),
	'sqlite'   => array
	(
		'driver'             => 'sqlite',
		'default_expire'     => 3600,
		'database'           => APPPATH.'cache/kohana-cache.sql3',
		'schema'             => 'CREATE TABLE caches(id VARCHAR(127) PRIMARY KEY, tags VARCHAR(255), expiration INTEGER, cache TEXT)',
	),
	'file'    => array
	(
		'driver'             => 'file',
		'cache_dir'          => APPPATH.'cache',
		'default_expire'     => 3600,
	)
);