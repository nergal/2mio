<?php defined('SYSPATH') or die('No direct script access.');

Route::set('demos', 'demos(/<controller>(/<demo>))')
	->defaults(array(
		'directory'  => 'demo',
		'controller' => 'demo',
		'action'     => 'show',
	));
