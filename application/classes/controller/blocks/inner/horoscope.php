<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Blocks_Inner_Horoscope extends Blocks_Abstract
{
    public function render()
    {
    	$this->template = View::factory('blocks/inner/horoscope');

    	$user = $this->request->query('user');
		if ($user OR ($user = Auth::instance()->get_user())) {
		    if ( ! empty($user->user_birthday)) {
				$this->template->horo = ORM::factory('horoscope')->get_last();

				if ( ! empty($this->template->horo)) {
					$date = $user->user_birthday;
					$key = Helper::get_horo($date);
				}
		    }
		}

		if ($this->template->enabled = isset($key)) {
			$this->template->horo_name = $key;
		}
    }
}
