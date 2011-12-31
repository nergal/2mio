<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Blocks_Left_News extends Blocks_Abstract
{
    public function render()
    {
		$data = ORM::factory('article')->get_recommended(5, TRUE);
		$this->template->data = $data;
    }
}
