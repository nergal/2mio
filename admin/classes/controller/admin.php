<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin extends Controller_Abstract
{
	public function before()
	{
		parent::before();
	}

	public function action_index()
	{
		$this->template = View::factory('main/index');
	    $this->template->base = self::$base;
	}

	public function action_left()
	{
		if ($this->request->is_initial()) {
		    Asset::add_js(array(
				'/js/admin/left.js',
		    ));
			Asset::add_css(array(
				'/css/admin/left.css',
		    ));
		}

	    $this->template = View::factory('main/left');
	    $this->template->base = self::$base;
	    $obj = $this->auth->get_user();
	    $this->template->user = $obj->username;
        $this->template->email = $obj->email;
        $this->template->render();
	}
	
	public function action_content()
	{
	    $this->template = View::factory('main/content');
	    $this->template->base = self::$base;
	    $this->template->render();
	}
	
	public function action_cache()
	{
	    $this->template = View::factory('main/cache');
	    $id = (array) $this->request->post('id');
	    $tables = Model::factory('cache')->get_tables();
	    
	    if ( ! empty($id)) {
		$id = array_map(function($item) {
		    return trim($item);
		}, $id);
		
		$_success = $_errors = array();
		
		foreach ($id as $_id) {
		    if (in_array($_id, $tables) AND Model::factory('cache')->clear($_id)) {
			$_success[] = $_id;
		    } else {
			$_errors[] = $_id;
		    }
		}
	    
		$this->template->success = $_success;
		$this->template->errors  = $_errors;
	    }
	    
	    $this->template->tables = $tables;
	}
	
}
