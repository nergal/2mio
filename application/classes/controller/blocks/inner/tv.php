<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Blocks_Inner_TV extends Blocks_Abstract
{
    public function render()
    {
        $article = Jelly::factory('article');
        $articles = $article->get_last()->limit(4);

        $this->template->set('articles', $articles);
    }
}
