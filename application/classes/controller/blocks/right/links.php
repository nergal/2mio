<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Blocks_Right_Links extends Blocks_Abstract
{
    public function render()
    {
		$links = ORM::factory('links');

		$links = $links->get_lasts(5);
		
		$this->template = View::factory('blocks/right/links');
		$this->template
			->set('links', $links);
    }
}
