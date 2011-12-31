<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Blocks_Inner_Blog extends Blocks_Abstract
{
    public function render()
    {
		// $topic = ORM::factory('topic');
		// $topic = $topic->get_top_view(1);
		
		$forum = Model::factory('forum');
		
		$topic = $forum->get_blog_top_commented();
		$user_name = $forum->get_username($topic['user_id']);
			
		$this->template = View::factory('blocks/inner/blog');
		$this->template
			->set('topic', $topic)
			->set('user_name', $user_name);
    }
}
