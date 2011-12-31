<?php defined('SYSPATH') or die('No direct script access.');

abstract class Controller_Abstract extends Controller
{
	public $template = NULL;

	public $user = NULL;
	public $auth = NULL;
	public $cache = NULL;

	protected $comments = NULL;
	protected $rating = NULL;
	protected $abc = NULL;
	
	public static function add_static()
	{
		    Asset::add_js(array(
		    	'/js/jquery.js',
		    	'/js/jquery.tools.js',
		    	'/js/flowplayer.js',
		    	'/js/jeditable/jquery.jeditable.js',
		    	'/js/forms/jquery.forms.js',
		    	'/js/stars/jquery.MetaData.js',
		    	'/js/simplepager/scripts/quickpager.jquery.js',
		    	'/js/validation/jquery.validate.min.js',
		    	'/js/favorites.js',
		    	'/js/chosen/chosen/chosen.jquery.js',
		    	'/js/stars/jquery.rating.pack.js',    // NB: Этот плагин должен идти последним!
		    	'/js/ask.js',
		    	'/js/main.js',
		    	'/js/jquery.lightbox.pack.js',
		    ));

		    Asset::add_css(array(
                //'/css/branding.css',
		    	'/css/style.css',
		    	'/css/skin.css',
		    	'/css/tags.css',
		    	'/js/stars/jquery.rating.css',
		    	'/css/forms.css',
		    	'/js/chosen/chosen/chosen.css',
		    	'/css/error404.css',
                '/css/add_style.css',
		   		'/css/jquery.lightbox.css',
		   		'/css/popup-style.css',
		    ));
	}

	public function before()
	{
		parent::before();

		$this->auth = Auth::instance();
		$this->auth->auto_login();

		$this->user = $this->auth->get_user();

		$this->cache = Cache::instance('memcache');

		if ($this->request->is_initial()) {
		    self::add_static();
		}
	}

	public function enable_comments(Model_Abstract_Page $model)
	{
		$this->comments = View::factory()->get_blocks(
			array('comments' => array('model' => $model)),
			'inner'
		);
	}
	
	public function enable_abc(Model_Abstract_Section $model, $litera_num, $active_literas)
	{
		$this->abc = View::factory()->get_blocks(
			array(
				'abc' => array(
					'model' => $model,
					'litera_num' => $litera_num,
					'active_literas' => $active_literas
				)
			), 'inner');
	}	

	/**
	 * Включение голосования для материала
	 *
	 * @param Model_Abstract_Page $model    Модель материала
	 * @param boolean             $is_annon Разрешить анонимным пользователям голосовать
	 * @param boolean             $enabled  Разрешить ли голосование
	 */
	public function enable_rating(Model_Abstract_Page $model, $is_annon = FALSE, $enabled = TRUE)
	{
		$this->rating = View::factory()->get_blocks(
			array(
				'rating' => array(
					'model'    => $model,
					'is_annon' => $is_annon,
					'enabled'  => $enabled,
				)
			), 'inner');
	}

	public function after()
	{
		if ($this->template !== NULL) {
			$this->template->bind('rating', $this->rating);
			$this->template->bind('comments', $this->comments);
			$this->template->bind('abc', $this->abc);
			$this->response->body($this->template->render());
		}

		return parent::after();
	}
}
