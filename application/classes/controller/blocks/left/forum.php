<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Blocks_Left_Forum extends Blocks_Abstract
{
    public function render()
    {
		$forum = Model::factory('forum');
		
		$topics_commented = $forum->get_top_commented(4);
		$topics_new = $forum->get_new(4);
		
		$topic_cnt = $forum->get_count_all();
		
		$this->template = View::factory('blocks/left/forum');
		$this->template
			->set('topics_commented', $topics_commented)
			->set('topics_new', $topics_new)
			->set('topic_cnt', $topic_cnt);
    }
}
