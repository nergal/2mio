<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Blocks_Inner_Abc extends Blocks_Abstract
{
    public function render()
    {
		$category = $this->request->query('model');
		$this->template->category = $category;
		
		$litera_num = $this->request->query('litera_num');
		$this->template->litera_num = $litera_num;
		
		$active_literas = $this->request->query('active_literas');
		$this->template->active_literas = $active_literas;
		
		$page = $this->request->query('page');
		$this->template->page = $page;
		
    }
}		
