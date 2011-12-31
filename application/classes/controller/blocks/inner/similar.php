<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Blocks_Inner_Similar extends Blocks_Abstract
{
    public function render()
    {
        $page = $this->request->query('page');
        $this->template->pages = $page->get_similar();
    }
}
