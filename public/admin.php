<?php

$application = '../admin';
$modules = '../modules';
$system = '../system';

define('EXT', '.php');

// define('DEBUG', TRUE);

error_reporting(E_ALL | E_STRICT);

define('DOCROOT', realpath(dirname(__FILE__)).DIRECTORY_SEPARATOR);

if ( ! is_dir($application) AND is_dir(DOCROOT.$application)) {
	$application = DOCROOT.$application;
}

if ( ! is_dir($modules) AND is_dir(DOCROOT.$modules)) {
	$modules = DOCROOT.$modules;
}

if ( ! is_dir($system) AND is_dir(DOCROOT.$system)) {
	$system = DOCROOT.$system;
}

// Define the absolute paths for configured directories
define('APPPATH', realpath($application).DIRECTORY_SEPARATOR);
define('MODPATH', realpath($modules).DIRECTORY_SEPARATOR);
define('SYSPATH', realpath($system).DIRECTORY_SEPARATOR);

defined('DEBUG') OR define('DEBUG', FALSE);

unset($application, $modules, $system, $public);

if (file_exists('install'.EXT)) {
	return include 'install'.EXT;
}

if ( ! defined('KOHANA_START_TIME')) {
	define('KOHANA_START_TIME', microtime(TRUE));
}

if ( ! defined('KOHANA_START_MEMORY')) {
	define('KOHANA_START_MEMORY', memory_get_usage());
}

if (DEBUG) {
	set_time_limit ( 0 );

	if (isset($_SERVER['KOHANA_ENV']) AND $_SERVER['KOHANA_ENV'] == 'development') {
    	if (extension_loaded('xhprof')) {
        	xhprof_enable(XHPROF_FLAGS_CPU + XHPROF_FLAGS_MEMORY);
    	}
	}
}

require APPPATH.'bootstrap'.EXT;

try {
    echo Request::factory()
	->execute()
	->send_headers()
	->body();
} catch (Exception $e) {
    throw $e;
}

if (DEBUG) {
	if (isset($_SERVER['KOHANA_ENV']) AND $_SERVER['KOHANA_ENV'] == 'development' AND ( ! Request::factory()->is_ajax())) {
	    if (extension_loaded('xhprof')) {
	        $xhprof_data = xhprof_disable();

	        include_once DOCROOT."../etc/xhprof/xhprof_lib/utils/xhprof_lib.php";
	        include_once DOCROOT."../etc/xhprof/xhprof_lib/utils/xhprof_runs.php";

	        $xhprof_runs = new XHProfRuns_Default;
	        $run_id = $xhprof_runs->save_run($xhprof_data, "xhprof_test");

	        echo "<div><p><a target='_blank' href='http://admin.nergal.likar.info/xhprof/index.php?run={$run_id}&source=xhprof_test'>XHProf report</a></p></div>\n";
	    } else {
	        echo "XHprof extension not loaded\n";
	    }
	}
}
