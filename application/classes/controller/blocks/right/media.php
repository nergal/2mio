<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Blocks_Right_Media extends Blocks_Abstract
{
	public function render()
	{
		$is_photo = $this->request->query('media') == 'photo';
		$data = ORM::factory($is_photo ? 'photo' : 'video')
			->where($is_photo ? 'informer_photo' : 'informer_video', '=', 1)
			->order_by('date', 'DESC')
			->visible()
			->limit(5)
			->find_all()
			->as_array();
		
		$this->template->is_photo = $is_photo;
		$this->template->data = $data;
	}
}
