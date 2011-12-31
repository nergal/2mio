<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Blocks_Double_Beautysex extends Blocks_Abstract
{
    public function render()
    {
		$article = ORM::factory('article');

		$sex = $article->get_by_section_id(305, 2, true);
		$beauty = $article->get_by_section_id(301, 2, true);
		
		$sex_sec = ORM::factory('section', 305);
		$beauty_sec = ORM::factory('section', 301);
		
		$this->template = View::factory('blocks/double/beautysex');
		$this->template
			->set('sex', $sex)
			->set('beauty', $beauty)
			->set('sex_sec', $sex_sec)
			->set('beauty_sec', $beauty_sec);
    }
}
