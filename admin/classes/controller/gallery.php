<?php

class Controller_Gallery extends Controller_Abstract
{
    /**
     * Инициализация 
     */
	public function before()
	{
		parent::before();
		
    }

	public function action_index()
	{
	    $this->template = View::factory('gallery/index');
	    $this->template->base = $this->base;
	}
	
}
