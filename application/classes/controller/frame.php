<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Контроллер для выброса блоков в forum
 *
 * @author     tretyak
 * @package    btlady
 * @subpackage blog
 */
class Controller_Frame extends Controller_Abstract
{
	public function action_menu()
	{
		$this->template = View::factory('forum/header');
	}
	
	public function action_right()
	{
		$this->template = View::factory('forum/right');
	}	
}
