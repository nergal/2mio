<?php
/**
 * Админка баннеров
 *
 * @author nergal
 * @package btlady
 * @subpackage admin
 */
class Controller_Banners extends Controller_Abstract
{
    /**
     * Инициализация
     */
	public function before()
	{
		parent::before();
		$this->view = View::factory('banners/index');
		$this->model = Model::factory('banner');
		$this->model->view = $this->view;
    }

	/*public function action_test()
	{
		var_dump(event::emit());
	}*/

	public function action_index()
	{
	    $this->template = $this->view;
		$this->template->domain = self::$domain;
	}

	public function action_getalljoins()
	{
		$id = $this->request->param('id', 1);
		$this->response->body($this->model->getAllJoins($id));
	}

	public function action_getallbanners()
	{
		$this->response->body($this->model->getAllBanners());
	}

	public function action_getallplaces()
	{
		$this->response->body($this->model->getAllPlaces());
	}
}