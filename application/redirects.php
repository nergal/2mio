<?php defined('SYSPATH') or die('No direct script access.');

Event::connect('redirects', function(Kohana_Request $request)
{
	if (PHP_SAPI != 'cli') {
		// Редирект по таблице
		$uri = $_SERVER['REQUEST_URI'];
		$redirect = ORM::factory('redirect', array('source' => $uri));
		if ($redirect->loaded()) {
			$request->redirect($redirect->destanation, 301);
		}
	}
});
