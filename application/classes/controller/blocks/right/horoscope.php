<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Blocks_Right_Horoscope extends Blocks_Abstract
{
    public function render()
    {
		$horo = ORM::factory('horoscope');
		$horo = $horo->get_last();

		$date = 'now';

		if ($user = Auth::instance()->get_user()) {
		    if ( ! empty($user->user_birthday)) {
				$date = $user->user_birthday;
		    }
		}

    	try {
			$date = new DateTime($date);
		} catch (Exception $e) {
			$date = new DateTime('now');
		}
		$key = Helper::get_horo($date);

		$this->template = View::factory('blocks/right/horoscope');
		$this->template
			->set('horo_name', $key)
			->set('horo', $horo);
	}
}
