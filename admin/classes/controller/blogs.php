<?php
/**
 * Controller_Blogs
 *
 * @author sokol
 * @package btlady-admin
 */
class Controller_Blogs extends Controller_Abstract
{
    /**
     * Инициализация 
     */
	public function before()
	{
		parent::before();
		$this->view = View::factory('blogs/index');
		$this->model = Model::factory('blogs');
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
	
	public function action_getblogs()
	{
		$this->model->getBlogs();
	}
	
    /**
     * Получить список объявлений
     */
    public function action_getblogitems($id)
    {
		$this->model->getBlogItems($id);
    }
    
    public function action_getcomments($id)
    {
        $this->model->getComments($id);
    }
	
	public function action_getallusers()
	{
		$this->model->getUsers();
	}
	
	public function action_getblogauthor($blog_id)
	{
		$this->model->getAuthor($blog_id);
	}
	
    public function action_getalltags()
    {
		$this->model->getAllTags();
	}
	
	public function action_getbindtags($blog_id = 0)
	{
		if($blog_id)
			$this->model->getBindTags($blog_id);
	}
}