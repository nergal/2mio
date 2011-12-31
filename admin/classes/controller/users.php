<?php
/**
 * Админка раздела "Пользователи"
 *
 * @author sokol
 * @package btlady-admin
 */
class Controller_Users extends Controller_Abstract
{
    /**
     * @var Fan_Model_Abstract
     */
    protected $model = NULL;

    /**
     * Хук. Инициализация лейаута
     *
     * @return void
     */
	public function before()
	{
		parent::before();
        $this->model = Model::factory('users');
    }

    /**
     * Отображение грида
     *
     * @return void
     */
    public function action_index()
    {
        $this->template = View::factory('users/index');
	}

    /**
     * AJAX-метод грида
     *
     * @return void
     */
    public function action_getallusers()
    {
        $this->model->get_all_users();
    }

	public function action_getallroles()
	{
		$this->model->get_all_roles();
	}	
	
	public function action_getbindroles($u_id)
	{
		$u_id = intval($u_id);
		
		if($u_id)
			$this->model->get_bind_roles($u_id);	
	}
}