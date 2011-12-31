<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Blocks_Inner_SocialComments extends Blocks_Abstract
{
    public function render()
    {
		$this->template->page = $this->request->query('page');
    }
}
