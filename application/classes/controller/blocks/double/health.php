<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Blocks_Double_Health extends Blocks_Abstract
{
    public function render()
    {
		$article = ORM::factory('article');

		$health = $article->get_by_section_id(304); // 30
		$shoping = $article->get_by_section_id(array(306, 307)); // 25, 21
		
		$health_sec = ORM::factory('section', 304);
		$shoping_sec = ORM::factory('section', 306);
		
		$this->template = View::factory('blocks/double/health');
		$this->template
			->set('health', $health)
			->set('shoping', $shoping)
			->set('health_sec', $health_sec)
			->set('shoping_sec', $shoping_sec);
    }
}
