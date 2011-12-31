<?php defined('SYSPATH') or die('No direct script access.');

/**
 * RSS контроллер
 *
 * @author tretyak
 * @package main
 */
class Controller_RSS extends Controller_Abstract
{

	public function after()
	{
		parent::after();
	}

	public function action_feed()
	{
		$section_id = $this->request->param('id', NULL);
		$this->template = View::factory('rss/feed');
		//$news = ORM::factory('article')->get_lasts(TRUE, 100, $section_id);
		$news = ORM::factory('article')->get_tree_lasts(TRUE, 50, $section_id);
		$this->template->news = $news;
		$this->response->headers('Content-Type', 'application/rss+xml; charset=UTF-8');
	}
	
	public function action_metabar()
	{
		$section_id = $this->request->param('id', NULL);
		$this->template = View::factory('rss/metabar');
		//$news = ORM::factory('article')->get_lasts(TRUE, 100, $section_id);
		$news = ORM::factory('article')->get_tree_lasts(TRUE, 50, $section_id);
		$this->template->news = $news;
		$this->response->headers('Content-Type', 'application/rss+xml; charset=UTF-8');
	}

	public function action_yandex()
	{
		$this->template = View::factory('rss/yandex');
		$news = ORM::factory('article')->get_ya_rss();
		$this->template->news = $news;
		$this->response->headers('Content-Type', 'application/rss+xml; charset=UTF-8');
	}
	
	public function action_mailru()
	{
		$this->template = View::factory('rss/mailru');
		$news = ORM::factory('article')->get_lasts(TRUE, 50);
		$this->template->news = $news;
		$this->response->headers('Content-Type', 'application/rss+xml; charset=UTF-8');
	}

	public function action_partners()
	{
		$this->template = View::factory('rss/partners');
		$news = ORM::factory('article')->get_partner_rss(50);
		$this->template->news = $news;
		$this->response->headers('Content-Type', 'text/xml; charset=UTF-8');		
	}
	
}
