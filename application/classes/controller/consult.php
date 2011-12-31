<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Контроллер консультаций
 *
 * @author     nergal
 * @package    btlady
 * @subpackage consultation
 */
class Controller_Consult extends Controller_Abstract
{
	/**
	 * Главная страница
	 *
	 * @uses consult/index
	 */
	public function action_index()
	{
	    $this->template = View::factory('consult/index');
	    $listing = ORM::factory('speciality')->find_all();

	    $this->template->specialities = $listing;
	}

	/**
	 * Список вопросов
	 *
	 * @uses  consult/category
	 *
	 * @param string  $category Имя категории
	 * @param integer $page     Страница
	 */
	public function action_list($category, $answer = 'all', $page = 1)
	{
		$limit = 10;

	    $this->template = View::factory('consult/category');

	    $answered = NULL;
	    if ($answer != 'all') {
	    	$answered = $answer == 'answered';
	    }

	    // TODO: инверсировать выборку модели для уменьшения кол-ва запросов
	    $model = ORM::factory('speciality')->get_section($category);

	    $listing = $model->questions->get_tree($category, $page, $limit, FALSE, $answered);
	    $count = $model->questions->get_count_tree($category, $answered);

	    $pagination_config = array(
			'current_page' => array('source' => 'route', 'key' => 'page'),
			'items_per_page' => $limit,
			'total_items' => $count,
	    );


	    $this->template->pager = Pagination::factory($pagination_config);
	    $this->template->listing = $listing;
	    $this->template->category = $model;
	}

	/**
	 * Страница просмотра статьи
	 *
	 * @throws HTTP_Exception_404
	 * @uses   consult/view
	 *
	 * @param  string  $category Часть адреса <category>
	 * @param  integer $id       Часть адреса <id>
	 * @param  string  $title    Часть адреса <title>
	 */
	public function action_view($category, $id, $title)
	{
		$this->template = View::factory('consult/view');
		$question = ORM::factory('question', $id)->valid($title);

		if ($question) {

			if ($post = $this->request->post()) {
				$user = Auth::instance()->get_user();

				$answer = ORM::factory('answer');

				$answer->body = $post['answer'];
				$answer->question = $question;
				$answer->consultant = ORM::factory('consultant', $user->id);
				$answer->checked = 1;
				$answer->ip = ip2long(Request::$client_ip);

				try {
					$answer->save();
				} catch (ORM_Validation_Exception $exception) {
					$this->template->errors = $exception->errors('validation');
				}
			}

			$this->template->question = $question;
		} else {
			throw new HTTP_Exception_404('Not found');
		}
	}

	public function action_admin()
	{
		$this->template = View::factory('consult/admin');
	}

	public function action_addform()
	{
		if ($this->request->is_initial() AND $this->request->method() != 'POST') {
			throw new HTTP_Exception_404('Not found');
		}

		$this->template = View::factory('consult/addform');
		$id = $this->request->query('id');

		if ($post = $this->request->post()) {
			$question = ORM::factory('question');

			$question->section_id = $post['speciality'];
			$question->title = $post['title'];
			$question->body = $post['body'];
			$question->doctor = ORM::factory('user', $post['consultant']);
			$question->showhide = 1;
			$question->ip = ip2long(Request::$client_ip);

			// TODO: валидация на консультанта

			if (isset($post['author']) AND ( ! empty($post['author']))) {
				$question->author = $post['author'];
			} else {
				// TODO: вынески в конфиг
				$question->author = 'Аноним';
			}

			if (Auth::instance()->logged_in('login')) {
				$question->user = Auth::instance()->get_user();
			}

			try {
				$question->save();

				$link = $this->template->uri($question);
				$this->request->redirect($link);

			} catch (ORM_Validation_Exception $exception) {
				$self->template->errors = $exception->errors('validation');
			}
		}

		// TODO: выобрка консультантов?
		$listing = ORM::factory('speciality')->find_all();
		$consultants = ORM::factory('speciality', $id)->consultants->find_all();

	    $this->template->specialities = $listing;
	    $this->template->consultants = $consultants;
	    $this->template->id = $id;
	}
}
