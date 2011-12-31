<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Blocks_Inner_Like extends Blocks_Abstract
{
    public function render()
    {
    	$title = urlencode($this->request->query('title'));
		$url = substr(URL::base('http'), 0, -1).$this->request->query('url');

		$this->template->short = $this->request->query('short');
		
		// 2 блока на страницу, нужно различать для javascript
		$this->template->order = $this->request->query('order');
		
		$this->template->title = $title;
		$this->template->url = $url;
    }
}
