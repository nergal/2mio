<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Blocks_Full_Menu extends Blocks_Abstract
{
    public function render()
    {
    	$main = ORM::factory('menu')->get_tree();
		$this->template->main = $main;
    }
}
