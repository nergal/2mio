<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Blocks_Double_Social extends Blocks_Abstract
{
    public function render()
    {
		$this->template->wide = $this->request->query('wide');
    }
}
