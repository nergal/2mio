<?php defined('SYSPATH') or die('No direct script access.');

class Mailer_User extends Mailer {

	public function before()
	{
		$this->config = Kohana::$environment;
	}
	
	public function welcome($args)
	{
		$args['title'] = 'Добро пожаловать в женский клуб "Хочу"!';
		$user = ORM::factory('user', $args['user']);
		
		if ($user->loaded()) {
			$this->to 		= array($user->user_email);
			$this->bcc      = array('y.lazebny@openmedia.com.ua', 's.eliseeva@openmedia.com.ua', 'nergal.dev@gmail.com');
			$this->type     = 'html';
			$this->from     = array('admin@bt-lady.com.ua' => 'Администрация сайта bt-lady.com.ua');
			$this->subject  = $args['title'];
			$this->news     = ORM::factory('article')->get_lasts(TRUE, 3);
			$this->data     = array(
								'user_id' => $user->user_id,
								'username' => $user->username,
								'news' => $this->news,
								'title' => $this->subject,
							);			
		} else {
			Kohana::$log->add(Log::ERROR, 'User '.$args['user'].' not found');
		}
	}
	
	public function confirm($args)
	{
		$email = isset($args['email']) ? $args['email'] : FALSE;
		$code = isset($args['code']) ? $args['code'] : FALSE;

		if ($email !== FALSE AND $code !== FALSE) {
			$args['title'] = 'Подтверждение регистрации на сайте';
			
			$this->to      = array($email);
			$this->from    = array('admin@bt-lady.com.ua' => 'Администрация сайта bt-lady.com.ua');
			$this->subject = $args['title'];
			$this->data    = $args;
		} else {
			Kohana::$log->add(Log::ERROR, 'Unable send confirmation email to '.$email);
		}
	}

	public function reset($args)
	{
		$email = isset($args['email']) ? $args['email'] : FALSE;
		$code = isset($args['code']) ? $args['code'] : FALSE;

		if ($email !== FALSE AND $code !== FALSE) {
			$args['title'] = 'Сброс пароля';
			
			$this->to      = array($email);
			$this->from    = array('admin@bt-lady.com.ua' => 'Администрация сайта bt-lady.com.ua');
			$this->subject = $args['title'];
			$this->data    = $args;
		} else {
			Kohana::$log->add(Log::ERROR, 'Unable send confirmation email to '.$email);
		}
	}

        public function topnews($sub_id)
	{
                $sub = ORM::factory('subscription', $sub_id);

		if ($sub->loaded()) {
                        $this->type    = 'html';
			$this->to      = array($sub->email);
			$this->from    = array('admin@bt-lady.com.ua' => 'Администрация сайта bt-lady.com.ua');
			$this->subject = 'Рассылка ТОП новостей';

                        $id = explode(',', $sub->sections);

                        $data = array();
                        foreach ($id as $_id) {
                            $_id = intVal(trim($_id));
                            $_data = ORM::factory('article')->get_lasts(TRUE, 3, $_id);
                            if ($_data->valid()) {
                                $data[$_data[0]->section->name] = $_data;
                            }
                        }

                        if (empty($data)) {
                            $this->to = array('a.poluhovich@openmedia.com.ua');
                        }

			$this->data = array('data' => $data, 'name' => $sub->username);
                        $unsubscribe_url = URL::site('user/unsubscribe/'.$sub->hash, TRUE);
                        $this->headers = array(
                            'List-Unsubscribe' => '<'.$unsubscribe_url.'>',
                        );
		} else {
			Kohana::$log->add(Log::ERROR, 'Unable send top news to '.$sub);
		}
	}

	public function subscribe($args)
	{
		$title = isset($args['title']) ? $args['title'] : 'Новое сообщение в вашей подписке';
		$email = isset($args['email']) ? $args['email'] : FALSE;
		$username = isset($args['username']) ? $args['username'] : FALSE;
		$data = isset($args['data']) ? $args['data'] : FALSE;

		if ($email !== FALSE AND $username !== FALSE AND $data !== FALSE) {
			$this->to      = array($email => $username);
			$this->from    = array('theteam@theweapp.com' => 'Администрация форума');
			$this->subject = $title;
			$this->data    = $args;
		} else {
			Kohana::$log->add(Log::ERROR, 'Unable send email to '.$email);
		}
	}

}
