<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Blocks_Right_Top extends Blocks_Abstract
{
    public function render()
    {
		$article = ORM::factory('article');

		$populars = $article->get_top_view(6);
		$discusses = $article->get_top_comment(6);
		
		$this->template = View::factory('blocks/right/top');
		$this->template
			->set('populars', $populars)
			->set('discusses', $discusses);
    }
}
