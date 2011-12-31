<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Контроллер информеров
 *
 * @author tretyak
 * @package main
 */
class Controller_Informers extends Controller_Abstract
{
	public function action_mailru()
	{
		$this->template = View::factory('informer/mailru');
		$informers = ORM::factory('rss')->get_news_informers('mailru');
		$this->template->informers = $informers;
	}
	
	public function action_aif($id = false)
	{
		if ($id == 'preview') {
			$this->template = View::factory('informer/aif-preview');
		} else {
			$this->response->headers('Content-Type', 'text/javascript; charset=UTF-8');			
			$this->template = View::factory('informer/aif');
		}
		$informers = ORM::factory('rss')->get_news_informers('aif');
		
		$info_arr = array();
		
		foreach ($informers as $informer) {
			$info_arr[] = $informer;
		}
		
		shuffle($info_arr);
		
		$this->template->informers = $info_arr;
	}	
}
