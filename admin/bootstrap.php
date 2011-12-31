<?php defined('SYSPATH') or die('No direct script access.');

require SYSPATH.'classes/kohana/core'.EXT;

if (is_file(APPPATH.'classes/kohana'.EXT)) {
	require APPPATH.'classes/kohana'.EXT;
} else {
	require SYSPATH.'classes/kohana'.EXT;
}

ini_set('unserialize_callback_func', 'spl_autoload_call');
spl_autoload_register(array('Kohana', 'auto_load'));

if (isset($_SERVER['KOHANA_ENV'])) {
	$env = 'Kohana::'.strtoupper($_SERVER['KOHANA_ENV']);
	Kohana::$environment = constant($env);
} elseif (defined('KOHANA_ENV')) {
	$env = 'Kohana::'.strtoupper(KOHANA_ENV);
	Kohana::$environment = constant($env);    
}

if (Kohana::$environment === Kohana::DEVELOPMENT) {
	$config = array(
		'timezone'  => 'Europe/Kiev',
		'locale'    => 'uk_UA.utf-8',
		'language'  => 'uk',
		'caching'   => FALSE,
		'profile'   => TRUE,
		'errors'    => TRUE,
		'charset'   => 'utf-8',
		'modules'   => 'asset, auth, cache, database, event, image, orm, plater',
		'cache_dir' => APPPATH.'cache',
		'logdir'    => APPPATH.'../etc/logs/application'
	);
} elseif (Kohana::$environment === Kohana::TESTING) {
	$config = array(
		'timezone'  => 'Europe/Kiev',
		'locale'    => 'en_US',
		'language'  => 'en-us',
		'caching'   => TRUE,
		'profile'   => FALSE,
		'errors'    => FALSE,
		'charset'   => 'utf-8',
		'modules'   => 'asset, auth, cache, database, event, image, orm, plater',
		'cache_dir' => APPPATH.'cache',
		'logdir'    => FALSE,
	);    
} else {
	$config = array(
		'timezone'  => 'Europe/Kiev',
		'locale'    => 'uk_UA.utf-8',
		'language'  => 'uk',
		'caching'   => TRUE,
		'profile'   => FALSE,
		'errors'    => FALSE,
		'charset'   => 'utf-8',
		'modules'   => 'asset, auth, cache, database, event, image, orm, plater',
		'cache_dir' => APPPATH.'cache',
		'logdir'    => FALSE,
	);
}

date_default_timezone_set($config['timezone']);
setlocale(LC_ALL, $config['locale']);

I18n::lang($config['language']);

Kohana::init(
    array(
		'base_url' => '/admin/',
		'index_file' => '/',
		'caching'  => $config['caching'],
		'profile'  => $config['profile'],
		'errors'   => $config['errors'],
		'charset'  => $config['charset'],
		'cache_dir' => $config['cache_dir'],
    )
);

if ($config['logdir'] !== FALSE) {
	Kohana::$log->attach(new Log_File($config['logdir']));
}

Cookie::$salt = 'sQs4g7NAR3ksqZGnc2wuNw6XBPCQJryuB246WBChbnKASxmfMU4R5X327D3j';

Kohana::$config->attach(new Config_File);

$modules = array();
foreach (explode(',', $config['modules']) as $module) {
	$name = trim($module);
	$modules[$name] = MODPATH.$name;
}
Kohana::modules($modules);

require APPPATH.'routing'.EXT;
