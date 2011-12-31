<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Virtualmakeover extends Controller_Abstract
{

	public function action_index()
	{
	    $this->template = View::factory('virtualmakeover/index')
			->set('no_right', 'true');
	}

}
