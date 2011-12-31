<?php defined('SYSPATH') or die('No direct script access.');

abstract class Controller_Subdomain extends Controller_Abstract
{
	public function before()
	{
	    $controller = $this->request->controller();
	    $server = $_SERVER['HTTP_HOST'];
	    
	    if ( ! preg_match('#^'.$controller.'#i', $server)) {
		throw new HTTP_Exception_418('Try another uri');
	    }

	    parent::before();
	}
}
