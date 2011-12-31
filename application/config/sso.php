<?php defined('SYSPATH') or die('No direct access allowed.');

return array(

	'twitter' => array(
		// URL where the user should be redirected to from the providers page
		'callback' => 'user/twitter',

		// URL where the user should be returned to if login process has to be restarted
		'login' => '111111111111111',
	),
	'facebook' => array(
		// URL where the user should be redirected to from the providers page
		'callback' => 'user/facebook',

		// URL where the user should be returned to if login process has to be restarted
		'login' => '22222222222222',
	),
	'vkontakte' => array(
		// URL where the user should be redirected to from the providers page
		'callback' => 'user/vkontakte',

		// URL where the user should be returned to if login process has to be restarted
		'login' => '3333333333333',
	),

);