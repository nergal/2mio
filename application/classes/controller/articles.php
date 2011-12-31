<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Основной контроллер
 *
 * @author nergal
 * @package main
 */
class Controller_Articles extends Controller_Abstract
{
	/**
	 * Главная страница
	 *
	 * @uses articles/category
	 *
	 * @param string  $category Имя категории
	 * @param integer $page     Страница
	 */
	public function action_index()
	{
		$limit = 10;

		$category = $this->request->param('category');
		$page     = $this->request->param('page', 1);
		
		$is_wiki = (substr($category, 0, 4) == 'wiki') ? TRUE : FALSE;
		
		$order = ($is_wiki) ? 'abc' : $this->request->param('order');
		$period = $this->request->param('period');
		
	    $this->template = View::factory('articles/category');
	    $model = ORM::factory('section')->get_section($category);

	    if ( ! $model->loaded()) {
		throw new HTTP_Exception_404('Not found');
	    }
	    
		/*if (preg_match('/perfumery/', $category)) {
			Asset::add_css(array('/css/branding-aromart.css'));
			Asset::add_js(array('/js/branding-aromart.js'));
			$this->template->zpixel = '<img src="http://ad.adriver.ru/cgi-bin/rle.cgi?sid=1&ad=290829&bt=21&pid=704479&bid=1400355&bn=1400355&rnd=1956307750" border=0 width=1 height=1>';
		}*/
	    
	    //  исключить wiki-категории из алфавитного рубрикатора
	    if (in_array($model->id, array(428, 429, 433))) {
			$order = 'date';
		}
	    
	    // wiki и алф. рубрикатор включен
	    if ($order == 'abc') {
			$litera_num = $this->request->param('litera');
			$litera_num = (! isset($litera_num) || $litera_num == '') ? -1 : $litera_num;
			
			$active_literas = $model->page->get_active_literas($category);
			$this->enable_abc($model, $litera_num, $active_literas);
			
			$listing = $model->page->get_tree_by_litera($category, $page, $limit, FALSE, $litera_num);
			$count = $model->page->get_tree_by_litera($category, NULL, NULL, TRUE, $litera_num);
		// вирутальный раздел recommends ("Хочу" рекомендует)
		} elseif(substr($category, 0, 10) == 'recommends') {
			$listing = $model->page->get_tree_recommend($page, $limit, FALSE, $order, $period);
			$count = $model->page->get_count_tree_recommend($period);
		// все остальные разделы
		} else {
			$listing = $model->page->get_tree($category, $page, $limit, FALSE, $order, $period);
			$count = $model->page->get_count_tree($category, $period);
		}

	    $pagination_config = array(
			'current_page' => array('source' => 'route', 'key' => 'page'),
			'items_per_page' => $limit,
			'total_items' => $count,
	    );

		if (($user = Auth::instance()->get_user())) {
			$cooking = ORM::factory('section')->get_childs('house/cook');
			$flag = $user->service_notepad;
			if (in_array($model->id, $cooking)) {
				$flag = $user->service_cookbook;
			}

			if ($flag) {
				$this->template->favorite = array_map(function($item) {
					return $item->page_id;
				}, ORM::factory('favorite')
					->where('favorites.user_id', '=', $user)
					->find_all()
					->as_array()
				);
			}
		}

		Meta::get('pages', array('section' => $model, 'name' => $model->name));

	    $this->template->pager = Pagination::factory($pagination_config);
	    $this->template->pages = $listing;
	    $this->template->section = $model;
	    $this->template->page = $page;
	    $this->template->order = $order;
	    $this->template->orders = array(
	    	'date' => 'дате',
	    	'views' => 'просмотрам',
	    	'comments' => 'комментариям',
	    );
		$this->template->period = $period;
		$this->template->periods = array(
			'week' => 'за неделю',
			'month' => 'за месяц',
			'all' => 'за все время',
		);
		$this->template->wiki = $is_wiki;
	}

	public function action_tag()
	{
		$this->template = View::factory('articles/category');

		$tag = $this->request->param('tag');
		$tag = str_replace('+', ' ', $tag);
		$tag_model = ORM::factory('tag')
			->where('name', '=', $tag)
			->find();

		$tags = $tag_model->pages->find_all();
		
		Meta::get('pages', array(
		    'name' => $tag_model->name,
		));

		$this->template->section = NULL;
		$this->template->order = NULL;
		$this->template->page = NULL;
		$this->template->pager = NULL;
		$this->template->pages = $tags;
		$this->template->tag = $tag;

		if (($user = Auth::instance()->get_user()) AND $user->service_notepad) {
			$this->template->favorite = array_map(function($item)
			{
				return $item->page_id;
			}, ORM::factory('favorite')
				->where('favorites.user_id', '=', $user)
				->find_all()
				->as_array()
			);
		}
	}

	/**
	 * Страница просмотра статьи
	 *
	 * @throws HTTP_Exception_404
	 * @uses   articles/view
	 *
	 * @param  string  $category Часть адреса <category>
	 * @param  integer $id       Часть адреса <id>
	 * @param  string  $title    Часть адреса <title>
	 */
	public function action_view($category, $id, $title)
	{
		$this->template = View::factory('articles/view');

		$route_name = Route::name($this->request->route());
		$model_name = (substr($route_name, 0, 4) == 'wiki') ? 'wiki' : 'article';
		$article = ORM::factory($model_name, $id)->valid($title);

		if ($article->loaded()) {

			$category = $article->section->name_url;
			/*if (preg_match('/perfumery/', $category)) {
				Asset::add_css(array('/css/branding-aromart.css'));
				Asset::add_js(array('/js/branding-aromart.js'));
				$this->template->zpixel = '<img src="http://ad.adriver.ru/cgi-bin/rle.cgi?sid=1&ad=290829&bt=21&pid=704479&bid=1400355&bn=1400355&rnd=1956307750" border=0 width=1 height=1>';
			}*/			
			
			if (isset($article->social_comments) AND $article->social_comments == 1) {
				$this->template->social_comments = TRUE;
				$this->template->no_right = TRUE;
			} else {
				$this->enable_comments($article);
			}			
			
			$this->template->article = $article;
			list($list, $count) = ORM::factory('section')
				->get_tree($article->section->parent_id);

			if (($user = Auth::instance()->get_user())) {
				$cooking = ORM::factory('section')->get_childs('house/cook');
				$flag = $user->service_notepad;
				if (in_array($article->section->id, $cooking)) {
					$flag = $user->service_cookbook;
				}

				if ($flag) {
					$this->template->favorite = ORM::factory('favorite')
						->where('favorites.user_id', '=', $user)
						->where('favorites.page_id', '=', $article)
						->find();
				}
			}

			/** Media */
			$media = $article->media
					->order_by('orders', 'ASC')
					->order_by('date', 'ASC')
					->find_all()
					->as_array();
					
			if (count($media) > 0) {
				$photo_id = $this->request->param('photo_id', 1);

				if ($photo_id < 1) {
					$photo_id = 1;
				} elseif ($photo_id > count($media)) {
					$photo_id = count($media);
				}

				$photo_id--;

				if ( ! (isset($media[$photo_id]) AND $media[$photo_id] instanceof Model_Media)) {
					$photo_id = 0;
				}

				$current = $media[$photo_id];

				$pagination_config = array(
					'current_page' => array('source' => 'route', 'key' => 'photo_id'),
					'items_per_page' => 1,
					'total_items' => count($media),
					'view' => 'floating',
			    );
				$this->template->photo_pager = Pagination::factory($pagination_config);
				$this->template->media = $current;
			}

			Meta::get('pages', array(
			    'name' => $article->title,
			    'desc' => $article->description,
			    'page' => $article,
			));

			$this->template->sections = $list;
		} else {
			throw new HTTP_Exception_404('Not found');
		}
	}

	public function action_all()
	{
		$this->template = View::factory('articles/all');
		$this->template->categories = ORM::factory('section')
			//->where('sections.parent_id', 'IS', NULL)
			->where('sections.parent_id', '=', 526)
			->where('sections.showhide', '=', 1)
			->where('sections.id', '!=', 306) // исключить "Отдых"
			->where('type.alias', '=', 'category')
			//->where('sections.id', '!=', 451) // old sections
			//->where('sections.id', 'NOT IN', array(477, 420, 436)) // исключить разделы
			->order_by('order')
			->find_all();
	}

	/**
	 * ajax-выборка статей для блока TV
	 *
	 * @uses   blocks/inner/tv-pages
	 *
	 * @param  integer $page     post параметр переданный через ajax
	 */
	public function action_getforblock()
	{
	    if ( ! $this->request->is_ajax()) {
		throw new HTTP_Exception_404('Not found');
	    }
			$page = $this->request->post('page');

			$article = ORM::factory('page');
			$articles = $article->get_last_for_tv_block($page, 1);

			if (count($articles) > 0) {
				$this->template = View::factory('blocks/inner/tv');
				$this->template
					->set('articles', $articles)
					->set('is_ajax', true)
					->set('page', $page)
					->set('comment_cnt', $articles[0]->comments_count);
			}
	}

	public function action_setfavorite()
	{
		if ($this->request->is_ajax())
		{
			$user = Auth::instance()->get_user();
			if ($user->loaded())
			{
				$this->response->headers('Content-type','application/json; charset='.Kohana::$charset);

				$page_id = $this->request->post('page_id');
				$action = $this->request->post('action');

				$article = ORM::factory('page', $page_id);
				$response = array('page_id' => $page_id, 'action' => $action);

				if ($action == 'add' AND $article->loaded()) {
					$favorite = ORM::factory('favorite');
					$favorite->user = $user;
					$favorite->article = $article;

					try {
						$favorite->save();

						$response['result'] = true;
					} catch(Database_Exception $e) {
						$response['result'] = false;
						// $response['debug'] = $e->getMessage();
					}
				} elseif ($action == 'remove' AND $article->loaded()) {
					$bookmark = ORM::factory('favorite', array('favorites.user_id' => $user, 'favorites.page_id' => $article));
					try {
						$bookmark->delete();
						$response['result'] = true;
					} catch(Database_Exception $e) {
						$response['result'] = false;
						// $response['debug'] = $e->getMessage();
					}
				} else {
					$response['result'] = false;
				}

				$this->response->body(json_encode($response));
			} else {
				throw new HTTP_Exception_403('Authorisation is required');
			}
		} else {
			throw new HTTP_Exception_404('Not found');
		}
	}

}
