<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Blocks_Double_Video extends Blocks_Abstract
{
    public function render()
    {
		$video = ORM::factory('video');
		$video = $video->get_lasts(TRUE, 10);

		$video_sec = ORM::factory('section', array('sections.name_url' => 'video'));

		$this->template = View::factory('blocks/double/video');
		$this->template
			->set('videos', $video)
			->set('video_sec', $video_sec);
    }
}
