<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Blocks_Double_Starsfashion extends Blocks_Abstract
{
    public function render()
    {
		$article = ORM::factory('article');

		$stars = $article->get_by_section_id(302, 2, true);
		$fashion = $article->get_by_section_id(303, 2, true);
		
		$stars_sec = ORM::factory('section', 302);
		$fashion_sec = ORM::factory('section', 303);
		
		$this->template = View::factory('blocks/double/starsfashion');
		$this->template
			->set('stars', $stars)
			->set('fashion', $fashion)
			->set('stars_sec', $stars_sec)
			->set('fashion_sec', $fashion_sec);
    }
}
