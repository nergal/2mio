<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Основной контроллер
 *
 * @author nergal
 * @package main
 */
class Controller_Main extends Controller_Abstract
{

	/**
	 * Главная страница
	 *
	 * @uses main/index
	 */
	public function action_index()
	{
	    Meta::get('main');
	    $this->template = View::factory('main/index');
	    $this->template->index = true;
	}

	/**
	 * Главная страница гороскопов
	 *
	 * @uses main/horoscope
	 */
	public function action_horoscope()
	{
	    Meta::get('horoscope');
	    $this->template = View::factory('main/horoscope');
	    $model = ORM::factory('horoscope')->get_last();
	    $this->template->signs = $model;
	}

	public function action_pages($slug)
	{
	    $page = ORM::factory('page', array('pages.name_url' => $slug));
	    if ($page->loaded()) {
		$this->template = View::factory('main/page');
		$this->template->page = $page;

		Meta::get('pages', array(
		    'name' => $page->title,
		    'desc'  => $page->description,
		));
	    } else {
		throw new HTTP_Exception_404;
	    }
	}

	public function action_consult()
	{
	    $this->template = View::factory('main/consult');

	    $cwd = getcwd();
	    if (chdir('/var/www/bt-lady/htdocs/')) {
			error_reporting(0);

			ob_start();
			require_once('./index.php');
			$html = ob_get_clean();

			if (preg_match('#<div class="corners_svez" style="position:relative"><div class="corners_1"><div class="corners_2"><div class="corners_3"><div class="corners_4">(?P<content>.+?)</div></div></div></div></div>#msi', $html, $matches)) {
			    $content = preg_replace('#src="i#', 'src="http://consult.bt-lady.com.ua/i', $matches['content']);
			    $content = str_replace('index.php', '', $content);
			    $this->template->data = mb_convert_encoding($content, 'UTF-8', 'cp1251');
			}

			error_reporting(E_ALL);
	    }
	    chdir($cwd);

	}
}
