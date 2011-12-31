<?php
/**
 * Админка RSS информеров
 *
 * @author tretyak
 * @package btlady
 * @subpackage admin
 */
class Controller_Informers extends Controller_Abstract
{
    /**
     * Инициализация
     */
	public function before()
	{
		parent::before();
		$this->view = View::factory('informers/index');
		$this->model = Model::factory('informer');
		$this->model->view = $this->view;
    }

	public function action_index()
	{
	    $this->template = $this->view;
		$this->template->domain = self::$domain;
	}
	
	/**
	 * Получение списка разделов
	 */	
	public function action_sections()
	{
		Model::factory('pages')->get_sections($this->request);
	}

	/**
	 * Получение списка материалов по текущему разделу
	 */
	public function action_pages($id = null)
	{
		Model::factory('informer')->get_pages($id);
	}
	
	/**
	 * Получение списка материалов для rss ленты по id
	 */	
	public function action_rss($id = 0)
	{
        Model::factory('informer')->get_rss($id);
	}
}
