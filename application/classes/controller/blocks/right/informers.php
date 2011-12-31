<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Blocks_Right_Informers extends Blocks_Abstract
{
    public function render()
    {
		$name = $this->request->query('name');
		$this->template = View::factory('blocks/right/informer-'.$name);
		
		
    }
}
