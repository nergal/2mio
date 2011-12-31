<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Контроллер галерей
 *
 * @author nergal
 * @package main
 */
class Controller_Gallery extends Controller_Abstract
{
	/**
	 * Главная страница
	 *
	 * @uses gallery/index
	 *
	 * @param integer $page     Страница
	 */
	public function action_index($page = 1)
	{
	    $this->template = View::factory('gallery/index');
	    $model = ORM::factory('photo');

	    $new_photo = $model
			->where('pages.showhide', '=', 1)
			->order_by('pages.date', 'DESC')
			->group_by('pages.section_id')
			->limit(3)->find_all();
	    $viewed_photo = $model
			->where('pages.showhide', '=', 1)
			->order_by('pages.views_count', 'DESC')
			->group_by('pages.section_id')
			->limit(3)->find_all();
	    $commented_photo = $model
			->where('pages.showhide', '=', 1)
			->order_by('pages.comments_count', 'DESC')
			->group_by('pages.section_id')
			->limit(3)->find_all();

	    $listing = array(
	    	'Свежие фото' => $new_photo->as_array(),
	    	'Самые просматриваемые галереи' => $viewed_photo->as_array(),
	    	'Самые комментируемые галереи' => $commented_photo->as_array(),
	    );

	    $this->template->galleries = $listing;
	}

	/**
	 * Главная страница
	 *
	 * @uses gallery/category
	 *
	 * @param string  $category Имя категории
	 * @param integer $page     Страница
	 */
	public function action_list($category, $page = 1)
	{
		$limit = 15;

	    $this->template = View::factory('gallery/list');
	    $section = ORM::factory('gallery')->get_section($category);

	    if ($section->loaded()) {
		    $model = $section->page;
		    $model->reset(FALSE);
		    $count = $model->where('pages.showhide', '=', 1)->count_all();
		    $offset = $limit * (intVal($page) - 1);

		    $model->limit($limit)->offset($offset);
		    $listing = $model->find_all();

		    $pagination_config = array(
				'current_page' => array('source' => 'route', 'key' => 'page'),
				'items_per_page' => $limit,
				'total_items' => $count,
		    );

		    $this->template->pager = Pagination::factory($pagination_config);
		    $this->template->photos = $listing->as_array();
		    $this->template->gallery = $section;
	    } else {
	    	throw new HTTP_Exception_404('Not found');
	    }
	}

	public function action_addphoto($category)
	{
		$this->template = View::factory('gallery/add');
		// FIXME: HARDCODE!!!
		$is_contest_section = ($category == 'BL-foto-c-lady-0316');
		$section = ORM::factory($is_contest_section ? 'contestgallery' : 'gallery')->get_section($category);

		if ($section->loaded()) {
			$this->template->gallery = $section;
			$this->template->is_contest_section = $is_contest_section;
			$this->template->data = array();

			if ($this->request->method() == 'POST') {
				try {
					$data = $this->request->post();

					$file = Validation::factory($_FILES);
					$file->rule('file', 'Upload::size', array(':value', '5M'));
					$file->rule('file', 'Upload::not_empty');
					$file->rule('file', 'Upload::type', array(':value', array('jpg', 'png', 'gif')));
					$file->rule('file', 'Upload::valid');

					if ($file->check()) {
						$filename = $file['file']['tmp_name'];
						$extension = strtolower(pathinfo($file['file']['name'], PATHINFO_EXTENSION));
						$new_filename = md5_file($filename).uniqid().'.'.$extension;

						$path = array(DOCROOT.'uploads');
						$path[] = substr($new_filename, 0, 2);
						$path[] = substr($new_filename, 2, 4);
						$path = implode(DIRECTORY_SEPARATOR, $path);

						if ( ! realpath($path)) {
							mkdir($path, 0777, TRUE);
						}
						Upload::save($file['file'], $new_filename, $path);

						$model = ($is_contest_section) ? 'contestphoto' : 'photo';
						$photo = ORM::factory($model);

						$photo->section_id = $section;
						$photo->photo = $new_filename;
						$photo->showhide = 0;
						$photo->fix = 0;
						$photo->date = date('Y-m-d H:i:s');
						$photo->type_id = 5;

						$expected = array('title', 'description', 'email', 'phone');
						if ($photo instanceof Model_Contestphoto) {
							$expected = array_merge($expected, array('age', 'social_state'));
							$photo->subscribe_seldiko = (isset($data['subscribe_seldiko']) AND $data['subscribe_seldiko'] == 'yes');
						}
						if ($this->auth->logged_in()) {
							$photo->user_id = $this->user;
						} else {
							$expected[] = 'author';
						}
						$photo->values($data, $expected);
						$photo->save();

						$this->template->success = array('Ваша фотография успешно добавлена и появиться на сайте после одобрения модератором.');
					} else {
						throw new ORM_Validation_Exception('gallery', $file);
					}

				} catch (Kohana_Exception $e) {
					$errors = $e->getMessage();
					if ($e instanceof ORM_Validation_Exception) {
						$errors = $e->errors('validation');
					}
					$this->template->errors = $errors;
					$this->template->data = $data;
				}
			}
		} else {
			throw new HTTP_Exception_404('Not found');
		}
	}

	/**
	 * Страница просмотра статьи
	 *
	 * @throws HTTP_Exception_404
	 * @uses   gallery/view
	 *
	 * @param  string  $category Часть адреса <category>
	 * @param  integer $id       Часть адреса <id>
	 */
	public function action_view($category, $id)
	{
		$is_contest_section = ($category == 'BL-foto-c-lady-0316');
		$model = ($is_contest_section) ? 'contestphoto' : 'photo';

	    if ($this->request->is_ajax()) {
			$photo = ORM::factory($model, $id);

			if ($photo->loaded()) {
				$this->enable_rating($photo);
			} else {
				throw new HTTP_Exception_404('Not found');
			}
	    } else {
			$this->template = View::factory('gallery/view');
			$this->template->errors = FALSE;

			$photo = ORM::factory($model)
				->where('pages.id', '=', $id)
				->where('pages.showhide', '=', 1)
				->find();

			if ($photo->loaded()) {
				$this->enable_comments($photo);

				$votes_active = ($photo->section->votes_active) ? TRUE : FALSE;
				$this->enable_rating($photo, FALSE, $votes_active);

				$this->template->photo = $photo;
				$hack_to_call_body_for_views_increment = $photo->body;
			} else {
				throw new HTTP_Exception_404('Not found');
			}
		}
	}
}
