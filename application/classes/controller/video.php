<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Контроллер видео-галерей
 *
 * @author nergal
 * @package main
 */
class Controller_Video extends Controller_Abstract
{
	/**
	 * Главная страница
	 *
	 * @uses video/index
	 *
	 * @param integer $page     Страница
	 */
	public function action_index($page = 1)
	{
	    $this->template = View::factory('video/index');
	    $model = ORM::factory('video');
	    
	    Meta::get('videogallery');

	    $new_video = $model
		->where('section.showhide', '=', 1)
		->where('pages.showhide', '=', 1)
		->order_by('pages.date', 'DESC')
		->group_by('pages.section_id')
		->limit(3)->find_all();

	    $viewed_video = $model
		->where('section.showhide', '=', 1)
		->where('pages.showhide', '=', 1)
		->order_by('pages.views_count', 'DESC')
		->group_by('pages.section_id')
		->limit(3)->find_all();

	    $commented_video = $model
		->where('section.showhide', '=', 1)
		->where('pages.showhide', '=', 1)
		->order_by('pages.comments_count', 'DESC')
		->group_by('pages.section_id')
		->limit(3)->find_all();

	    $listing = array(
	    	'Свежие видео' => $new_video->as_array(),
	    	'Самые просматриваемые видеогалереи' => $viewed_video->as_array(),
	    	'Самые комментируемые видеогалереи' => $commented_video->as_array(),
	    );

	    $this->template->galleries = $listing;
	}

	/**
	 * Главная страница
	 *
	 * @uses video/category
	 *
	 * @param string  $category Имя категории
	 * @param integer $page     Страница
	 */
	public function action_list($category, $page = 1)
	{
		$limit = 15;

	    $this->template = View::factory('video/list');
	    $section = ORM::factory('videogallery')->get_section($category);

	    if ($section->loaded()) {
		    $model = $section->page;
		    $model->reset(FALSE);
		    $model->where('pages.showhide', '=', 1); // фильтр показывать только активные статьи
		    $count = $model->count_all();
		    $offset = $limit * (intVal($page) - 1);

		    $model->limit($limit)->offset($offset);
		    $listing = $model->find_all();

		    $pagination_config = array(
				'current_page' => array('source' => 'route', 'key' => 'page'),
				'items_per_page' => $limit,
				'total_items' => $count,
		    );

		    $this->template->pager = Pagination::factory($pagination_config);
		    $this->template->videos = $listing->as_array();
		    $this->template->gallery = $section;
	    } else {
	    	throw new HTTP_Exception_404('Not found');
	    }
	}

	/**
	 * Страница просмотра статьи
	 *
	 * @throws HTTP_Exception_404
	 * @uses   video/view
	 *
	 * @param  string  $category Часть адреса <category>
	 * @param  integer $id       Часть адреса <id>
	 */
	public function action_view($category, $id)
	{
		$this->template = View::factory('video/view');
		$this->template->errors = FALSE;

		$video = ORM::factory('video', $id);

		if ($video) {
			$this->enable_comments($video);
			$this->enable_rating($video);

			$this->template->video = $video;
			$hack_to_call_body_for_views_increment = $video->body;
			$this->template->is_ajax = $this->request->is_ajax();

			if (($user = Auth::instance()->get_user()) AND $user->service_video) {
				$this->template->favorite = ORM::factory('favorite')
					->where('favorites.user_id', '=', $this->user)
					->where('favorites.page_id', '=', $video)
					->find();
			}
		} else {
			throw new HTTP_Exception_404('Not found');
		}
	}
}
