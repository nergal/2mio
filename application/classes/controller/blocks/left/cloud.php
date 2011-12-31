<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Blocks_Left_Cloud extends Blocks_Abstract
{
    public function render()
    {
	$this->template->tags = ORM::factory('tag')->get_cloud();
    }
}
