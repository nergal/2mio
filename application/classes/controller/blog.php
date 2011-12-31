<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Контроллер блогов
 *
 * @author     tretyak
 * @package    btlady
 * @subpackage blog
 */
class Controller_Blog extends Controller_Abstract
{
	public function action_index()
	{
	    $this->template = View::factory('blogs/index')
			->bind('topics', $topics);
	    
	    $topics = ORM::factory('topic')->get_lasts(TRUE, 6);
	}	
	
	public function action_topic($category, $id, $title)
	{
		$this->_action_topic_all($category, $id, $title);
	}
	
	public function action_topic_operation($category, $id, $operation = 'view')
	{
		$this->_action_topic_all($category, $id, NULL, $operation);
	}
	
	private function _action_topic_all($category, $id, $title = NULL, $action = NULL)
	{
		$this->template = View::factory('blogs/topic');
		$topic = ORM::factory('topic', $id)->valid($title);
		
		$this->template->errors = FALSE;
		
		if ($topic) {
			$this->enable_comments($topic);
			$this->template->topic = $topic;
			list($list, $count) = ORM::factory('section')
				->get_tree($topic->section->parent_id);
			$this->template->sections = $list;		
		} else {
			throw new HTTP_Exception_404('Not found');			
		}
		
		$owner = TRUE; // $owner = ($this->user AND ($topic->user_id == $this->user->id)) ? TRUE : FALSE;
		$this->template->owner = $owner;
		$errors = array();

		if ($action == "edit") {
			$this->template->action = 'edit';
			
			if ($owner != TRUE) {
				throw new HTTP_Exception_403('You are not allowed to proceed this action');
			}

			if ($this->request->method() == 'POST') {
				$topic->values($this->request->post(), array('title', 'body'));
				try {
					$topic->update();
				} catch(ORM_Validation_Exception $exeption) {
					$this->template->errors = $exeption->errors('validation');
				}
			}
		} elseif ($action == "delete") {
			
			if ($owner != TRUE) {
				throw new HTTP_Exception_403('You are not allowed to proceed this action');
			}
			
			$topic->delete();
			$this->request->redirect('blog');
			
		} else {
			$this->template->action = 'view';
		} 

		$this->template->topic = $topic;
	}
	
	public function action_list($category, $page = 1)
	{
		$limit = 10;

	    $this->template = View::factory('blogs/category');
	    $model = ORM::factory('section')->get_section($category);

	    $listing = $model->page->get_tree($category, $page, $limit);
	    $count = $model->page->get_count_tree($category);

	    $pagination_config = array(
			'current_page' => array('source' => 'route', 'key' => 'page'),
			'items_per_page' => $limit,
			'total_items' => $count,
	    );

	    $this->template->pager = Pagination::factory($pagination_config);
	    $this->template->pages = $listing;
	    $this->template->section = $model;
	}
	
}
