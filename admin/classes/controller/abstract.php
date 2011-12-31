<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Общая логика контроллеров админки
 *
 * @author kolex
 * @package btlady-admin
 */
abstract class Controller_Abstract extends Controller
{
	public $template = NULL;
	public static $domain = NULL;
	public static $base = NULL;

	protected $auth = NULL;

	public function before()
	{
		//Kohana::$environment = Kohana::TESTING;

		$this->auth = Auth::instance();
		if (( ! $this->auth->logged_in('admin')) AND ( ! $this->auth->logged_in('moderator'))) {
			// Хак, но по RFC (rfc#2396 sec.5)
			$this->request->redirect('/../login/');
		}

		parent::before();

		self::$domain = $_SERVER['HTTP_HOST'];
		self::$base = 'http://' . self::$domain . url::base();

		if ($this->request->is_initial()) {
		    Asset::add_js(array(
		    	'/js/jquery.js',
		    	'/js/admin/common.js',
		    ));

		    Asset::add_css(array(
				'/css/admin/common.css',
		    ));
		}

	}

	public function get_hash_subdirs($picture)
	{
        $subdirs = "/" . substr($picture, 0, 2) . "/" . substr($picture, 2, 4) . "/";

        return $subdirs;
	}

	public function after()
	{
		if ($this->template !== NULL) {
			$this->response->body($this->template->render());
		}

		return parent::after();
	}
}
