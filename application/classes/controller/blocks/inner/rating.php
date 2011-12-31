<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Blocks_Inner_Rating extends Blocks_Abstract
{
    public function render()
    {
		$this->template->errors = FALSE;

		$page = $this->request->query('model');
		$this->template->page = $page;

		$is_annon = $this->request->query('is_annon');
		$enabled = $this->request->query('enabled');
		$enabled = $enabled AND ($is_annon OR Auth::instance()->logged_in('login'));

		$request = Request::initial();

		$hash = Session::instance()->id();
		$ip = ip2long(Request::$client_ip);
		$rating = ORM::factory('rating')->get_rating($page, $ip);

		$this->template->value = $rating->value;
		$this->template->counts = $rating->counts;
		$this->template->sum = $rating->sum;

		$enabled = $enabled & ( ! $rating->is_voted);

		$this->template->enabled = $enabled;
		$rate_val = $request->post('rating');

    	if ($enabled AND $request->method() == 'POST' AND $page->loaded() AND ! empty($rate_val)) {
    		$rating = ORM::factory('rating');

			if ($user = Auth::instance()->get_user()) {
				$rating->user = $user;
			}

			$rating->value = intVal($rate_val);
			$rating->hash  = $hash;
			$rating->ip    = $ip;
			$rating->page  = $page;
			$rating->date  = date('Y-m-d');

			try {
				try {
					$rating->save();
					
					$rating = ORM::factory('rating')->get_rating($page, $ip);
					$rating->sum = round($rating->sum);
					
					echo '<span class="avg">'.$rating->counts.'</span> '.(Helper::plural($rating->counts, 'голос', 'голоса', 'голосов')).', <span class="total">'.$rating->sum.'</span> '.(Helper::plural($rating->sum, 'балл', 'балла', 'баллов'));
					die();
					
				} catch (ORM_Validation_Exception $exception) {
					$this->template->errors = $exception->errors('validation');

					echo implode("\n", $this->template->errors);
					die();
				}
			} catch (Database_Exception $e) {
				if ($e->db_code() == 1062) {
					echo 'Нельзя голосовать повторно';
					die();
				}
			}
			
			echo $response;
    	}
    }
}
