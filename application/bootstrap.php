<?php defined('SYSPATH') or die('No direct script access.');

// -- Environment setup --------------------------------------------------------

// Load the core Kohana class
require SYSPATH.'classes/kohana/core'.EXT;

if (is_file(APPPATH.'classes/kohana'.EXT)) {
	// Application extends the core
	require APPPATH.'classes/kohana'.EXT;
} else {
	// Load empty core extension
	require SYSPATH.'classes/kohana'.EXT;
}

/**
 * Enable the Kohana auto-loader for unserialization.
 *
 * @see  http://php.net/spl_autoload_call
 * @see  http://php.net/manual/var.configuration.php#unserialize-callback-func
 */
ini_set('unserialize_callback_func', 'spl_autoload_call');

/**
 * Enable the Kohana auto-loader.
 *
 * @see  http://kohanaframework.org/guide/using.autoloading
 * @see  http://php.net/spl_autoload_register
 */
spl_autoload_register(array('Kohana', 'auto_load'));

/**
 * Set Kohana::$environment if a 'KOHANA_ENV' environment variable has been supplied.
 *
 * Note: If you supply an invalid environment name, a PHP warning will be thrown
 * saying "Couldn't find constant Kohana::<INVALID_ENV_NAME>"
 */
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
		'locale'    => 'ru_RU.utf-8',
		'language'  => 'ru',
		'caching'   => FALSE,
		'profile'   => TRUE,
		'errors'    => TRUE,
		'charset'   => 'utf-8',
		'modules'   => 'apis/twitter, asset, auth, cache, bbcode, beanstalk, blocks, database, demo, event, image, mailer, meta, oauth, orm, pagination, plater, resizer, userguide, sso',
		'cache_dir' => APPPATH.'cache',
		'logdir'    => APPPATH.'../etc/logs/application',
		'salt'      => 'nd79Y!cPDG!SuWV$rWT8uHdJk%*T2ve84%#&9GCwN6c^5Hbj54^P$Ckx!8RH',
	);

	if (PHP_SAPI != 'cli') {
		 $config['modules'].= ', debug-toolbar';
	}
} elseif (Kohana::$environment === Kohana::TESTING) {
	$config = array(
		'timezone'  => 'Europe/Kiev',
		'locale'    => 'en_US',
		'language'  => 'en-us',
		'caching'   => TRUE,
		'profile'   => FALSE,
		'errors'    => FALSE,
		'charset'   => 'utf-8',
		'modules'   => 'apis/twitter, asset, auth, cache, bbcode, beanstalk, blocks, database, demo, event, image, mailer, meta, oauth, orm, pagination, plater, resizer, sso',
		'cache_dir' => APPPATH.'cache',
		'logdir'    => FALSE,
		'salt'      => '22sjmFA%$3uUb3d7AKG82A^unxGkdvMR9!5*47czjRWFFDBz!Bb@Q7Epf5^b',
	);
} else {
	$config = array(
		'timezone'  => 'Europe/Kiev',
		'locale'    => 'ru_RU.utf-8',
		'language'  => 'ru',
		'caching'   => TRUE,
		'profile'   => FALSE,
		'errors'    => FALSE,
		'charset'   => 'utf-8',
		'modules'   => 'apis/twitter, asset, auth, cache, bbcode, beanstalk, blocks, database, event, image, mailer, meta, oauth, orm, pagination, plater, resizer, sso',
		'cache_dir' => APPPATH.'cache',
		'logdir'    => APPPATH.'../etc/logs/application',
		'salt'      => '6wGwxJmevA$r92e5ZNmCK#C4UeET#$J2x%tA4E&R2HV8R5pvv^BtA@sjdT3f',
	);
}

/**
 * Set the default time zone.
 *
 * @see  http://kohanaframework.org/guide/using.configuration
 * @see  http://php.net/timezones
 */
date_default_timezone_set($config['timezone']);

/**
 * Set the default locale.
 *
 * @see  http://kohanaframework.org/guide/using.configuration
 * @see  http://php.net/setlocale
 */
setlocale(LC_ALL, $config['locale']);

// -- Configuration and initialization -----------------------------------------

/**
 * Set the default language
 */
I18n::lang($config['language']);

/**
 * Set cookie salt
 */
Cookie::$salt = $config['salt'];

/**
 * Initialize Kohana, setting the default options.
 *
 * The following options are available:
 *
 * - string   base_url    path, and optionally domain, of your application   NULL
 * - string   index_file  name of your index file, usually "index.php"       index.php
 * - string   charset     internal character set used for input and output   utf-8
 * - string   cache_dir   set the internal cache directory                   APPPATH/cache
 * - boolean  errors      enable or disable error handling                   TRUE
 * - boolean  profile     enable or disable internal profiling               TRUE
 * - boolean  caching     enable or disable internal caching                 FALSE
 */
Kohana::init(
    array(
		'base_url' => '/',
	    'index_file' => '/',
		'caching'  => $config['caching'],
		'profile'  => $config['profile'],
		'errors'   => $config['errors'],
		'charset'  => $config['charset'],
    )
);

if (PHP_SAPI == 'cli') {
    Kohana::$base_url = 'http://2mio.com/';
}

set_exception_handler(array('Exception_Handler', 'handle'));

/**
 * Attach the file write to logging. Multiple writers are supported.
 */
if ($config['logdir'] !== FALSE) {
	Kohana::$log->attach(new Log_File($config['logdir']));
}

/**
 * Attach a file reader to config. Multiple readers are supported.
 */
Kohana::$config->attach(new Config_File);

/**
 * Enable modules. Modules are referenced by a relative or absolute path.
 */
$modules = array();
foreach (explode(',', $config['modules']) as $module) {
	$name = trim($module);
	$modules[$name] = MODPATH.$name;
}
Kohana::modules($modules);

require APPPATH.'routing'.EXT;
require APPPATH.'redirects'.EXT;
