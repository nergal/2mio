<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Blocks_Inner_TV extends Blocks_Abstract
{
    public function render()
    {
		$page = 0;
		$article = ORM::factory('article');
		$articles = $article->get_last_for_tv_block($page);

		$this->template
			->set('articles', $articles)
			->set('is_ajax', false)
			->set('comment_cnt', $articles[0]->comments_count);
    }
}
