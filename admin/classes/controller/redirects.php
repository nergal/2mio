<?php
/**
 * Controller_Blogs
 *
 * @author kolex
 * @package btlady-admin
 */
class Controller_Redirects extends Controller_Abstract
{
    /**
     * Инициализация 
     */
	public function before()
	{
		parent::before();
		$this->view = View::factory('redirects/index');
		$this->model = Model::factory('redirects');
		$this->model->view = $this->view;
    }
	
	public function action_index()
	{
	    $this->template = $this->view;
		$this->template->domain = self::$domain;
	}
	
    /**
     * Получить список редиректов
     */
	public function action_getredirects()
	{
		$this->model->getRedirects();
	}
	
}