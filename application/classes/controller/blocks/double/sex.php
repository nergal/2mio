<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Blocks_Double_Sex extends Blocks_Abstract
{
    public function render()
    {
		$article = ORM::factory('article');

		$sex = $article->get_by_section_id(305);
		$fashion = $article->get_by_section_id(303);
		
		$sex_sec = ORM::factory('section', 305);
		$fashion_sec = ORM::factory('section', 303);
		
		$this->template = View::factory('blocks/double/sex');
		$this->template
			->set('sex', $sex)
			->set('fashion', $fashion)
			->set('sex_sec', $sex_sec)
			->set('fashion_sec', $fashion_sec);
    }
}
