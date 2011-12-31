<?php defined('SYSPATH') or die('No direct script access.');

class Controller_User extends Controller_Abstract
{
	public function action_index()
	{
		$url = '/login/';
		if ($this->auth->logged_in()) {
			$url = 'user/'.$this->user->id;
		}

		$this->request->redirect($url);
	}

	public function action_login()
	{
		$this->template = View::factory('user/login');

		$errors = array();

		if ($this->auth->logged_in()) {
			$this->request->redirect('user');
		}

		$referrer = $this->request->referrer();

		if ($_POST) {
			$username = Arr::get($_POST, 'email');
			$password = Arr::get($_POST, 'pass');
			$referrer = Arr::get($_POST, 'referrer');
			$remember = (bool) Arr::get($_POST, 'remember');

			try {
				$status = $this->auth->login($username, $password, $remember);

				if ($status) {
					$this->request->redirect($referrer);
				} else {
					$errors = array('Неверное имя пользователя или пароль!', 'Если Вы забыли пароль, Вы можете воспользоватся <a href="/reset/">восстановлением пароля</a>.');
				}
			} catch (ORM_Validation_Exception $e) {
				$errors = $e->errors('validation');
			}
		}

		$this->template->errors = $errors;
		$this->template->referrer = $referrer;
	}

	public function action_twitter()
	{
		$this->oauth_login('twitter', $this->request->referrer());
	}

	public function action_facebook()
	{
		$this->oauth_login('facebook', $this->request->referrer());
	}

	public function action_vkontakte()
	{
		$this->oauth_login('vkontakte', $this->request->referrer());
	}

	public function oauth_login($provider, $return)
	{
		if ( ! $this->auth->logged_in()) {
			try {
				if ( ! $this->auth->sso($provider)) {
					Kohana::$log->add(Log::ERROR, 'SSO Failed with '.$provider);
				}
			} catch (ORM_Validation_Exception $e) {
				Kohana::$log->add(Log::ERROR, $e->getMessage().' with '.$provider);
			}
		}

		$this->request->redirect('/');
	}

	public function action_logout()
	{
		$this->auth->logout();
		$uri = $this->request->referrer();
		$this->request->redirect($uri);
	}

	public function action_confirm($code)
	{
		if ( ! preg_match('#^[a-z0-9]{32}$#ui', $code)) {
			throw new HTTP_Exception_404('Not found');
		}

		$_code = ORM::factory('confirm', $code);

		$this->template = View::factory('user/login');
		$referrer = $this->request->referrer();
	    $this->template->referrer = $referrer;

		if ($_code->loaded()) {
			try {
				$_code->user->user_type = 0;
				$_code->user->add('roles', ORM::factory('role', array('name' => 'login')));
				$_code->user->update();

				$this->template->messages = array('Вы были успешно зарегистрированы и теперь можете войти, используя данные, который ввели ранее!');
				
				$data = array(
					//'email' => $user->user_email,
					'user' => $_code->user_id,
				);

				Queue::instance()->add('welcome', $data);
				// end welcome-mail   				
				
			} catch (ORM_Validation_Exception $e) {
				$this->template->errors = $e->errors('validation');
			}
		} else {
			$this->template->errors = array('Недействительный код подтверждения');
		}
	}

	public function action_register()
	{
	    if ($this->request->method() != 'POST')
	    {
			$this->request->redirect('/login/');
	    }

	    $this->template = View::factory('user/login');

	    $email = $this->request->post('email');
	    $pass = $this->request->post('pass');
	    $login = $this->request->post('login');

	    $referrer = $this->request->referrer();
	    $this->template->referrer = $referrer;

	    $model = ORM::factory('user');
	    $model->password = $pass;
    	$model->email = $email;
    	$model->username = $login;

	    $model->user_type = 1;

	    if ( ! empty($pass)) {
		    try {
				$model->save();

				$confirm = ORM::factory('confirm');
				$confirm->code = md5(microtime(TRUE).uniqid());
				$confirm->user = $model;

				$confirm->save();

				$data = array(
					'email' => $email,
					'code' => $confirm->code,
	    		);
	    		Queue::instance()->add('confirmation', $data);

				$this->template->info = array('Вам на почту было отправлено письмо со ссылкой для подтверждения регистрации.');
		    } catch (ORM_Validation_Exception $e) {
				$this->template->errors = $e->errors('validation');
		    }
	    } else {
	    	$this->template->errors = array('Пароль не должен быть пустым!');
	    }
	}

	public function action_edit()
	{
		if ($this->auth->logged_in()) {
			$this->template = View::factory('user/edit');

			if ($this->request->method() == 'POST') {
				$user = $this->auth->get_user();

				$post = $this->request->post();

				if (array_key_exists('services', $post) AND $post['services'] == 'yes') {
					$expected = $boolean_fields = array('service_notepad', 'service_blog', 'service_cookbook', 'service_video', 'service_beauty');
				} else {
					$expected = array('user_birthday', 'user_interests', 'user_from', 'user_icq', 'user_skype', 'user_website', 'city_id', 'lastname', 'firstname', 'show_email', 'show_birthday', 'user_email', 'username');
					$boolean_fields = array('show_birthday', 'show_email');
				}

				if (empty($user->username)
					AND $this->request->post('username')
					AND ! $user->has('roles', ORM::factory('role', array('name' => 'social')))) {
					$expected[] = 'username';
				}

				if ( ! $this->auth->logged_in('social')
					AND (isset($post['password']) AND $post['password'])
					AND (isset($post['password_confirm']) AND $post['password_confirm'])) {
					$expected = array_merge($expected, array('password'));
				}

				try {
					foreach ($boolean_fields as $field) {
						$post[$field] = (array_key_exists($field, $post) AND ($post[$field] == 'on'));
					}

					if (isset($post['user_birthday']) AND ! empty($post['user_birthday'])) {
						$date = new DateTime($post['user_birthday']);
						$post['user_birthday'] = $date->format('d-m-Y');
					}

					$user->update_user($post, $expected);
					$this->request->redirect('/user/');
				} catch (ORM_Validation_Exception $e) {
					$this->template->errors = $e->errors('validation');
					$user->reset();
				}
			}

			$user = $this->auth->get_user();
			if ($user->city->loaded()) {
				$city = $user->city;
			} else {
				$city = ORM::factory('city')->find_similar($user->user_from);
			}

			$this->template->user = $user;
			$this->template->country = $city->region->country;
			$this->template->countries = ORM::factory('country')->get_all();
			$this->template->cities = $this->template->country->get_cities(FALSE);
			$this->template->city_id = $city->id;
		} else {
			$this->request->redirect('/login/');
		}
	}

	public function action_avatar() 
	{
		if ($this->auth->logged_in()) {
			$this->template = View::factory('user/avatar');
			$user = $this->auth->get_user();

			if ($user->loaded()) {
				$user->reload_columns();

				if ($this->request->method() == 'POST') {
					try {
						$data = $this->request->post();

						$file = Validation::factory($_FILES);
						$file->rule('avatar', 'Upload::size', array(':value', '2M'));
						$file->rule('avatar', 'Upload::not_empty');
						$file->rule('avatar', 'Upload::type', array(':value', array('jpg', 'png', 'gif')));
						$file->rule('avatar', 'Upload::valid');

						if ($file->check()) {
							$filename = $file['avatar']['tmp_name'];
							$extension = strtolower(pathinfo($file['avatar']['name'], PATHINFO_EXTENSION));
							$new_filename = md5_file($filename).uniqid().'.'.$extension;

							$path = array(DOCROOT.'uploads');
							$path[] = substr($new_filename, 0, 2);
							$path[] = substr($new_filename, 2, 4);
							$path = implode(DIRECTORY_SEPARATOR, $path);

							if ( ! realpath($path)) {
								mkdir($path, 0777, TRUE);
							}
							Upload::save($file['avatar'], $new_filename, $path);

							$user->avatar = $new_filename;
							$user->update();

							$this->request->redirect('/login/');
						} else {
							throw new ORM_Validation_Exception('user', $file);
						}

					} catch (Kohana_Exception $e) {
						$errors = $e->getMessage();
						if ($e instanceof ORM_Validation_Exception) {
							$errors = $e->errors('validation');
						}
						$this->template->errors = $errors;
					}
				}
				$this->template->user = $user;
			}
		} else {
			$this->request->redirect('/login/');
		}
	}

	public function action_getcountry()
	{
		$id = $this->request->post('id');
		$model = ORM::factory('country', $id);

		$this->response->headers('Content-Type', 'application/json; charset=utf-8');

		$result = array('status' => 'faild',);

		if ($this->request->is_ajax() AND $model->loaded()) {
			$data = $model->get_cities(FALSE);

			$result['status'] = 'success';
			$result['result'] = $data;
		}
		return $this->response->body(json_encode($result));
	}

	public function action_view($id)
	{
		$this->template = View::factory('user/profile');
		$user = ORM::factory('user', $id);

		if ($user->loaded()) {
			if ($this->auth->logged_in() AND $user->id == $this->auth->get_user()->user_id) {
				$this->template = View::factory('user/private_profile');

			}

			if ($user->service_cookbook) {
				$this->template->favorites_cookbook = ORM::factory('favorite')
					->where('favorites.user_id', '=', $id)
					->having('article:section:id', 'IN', ORM::factory('section')->get_childs('house/cook'))
					->find_all();
			}

			if ($user->service_video) {
				$this->template->favorites_video = ORM::factory('favorite')
					->where('favorites.user_id', '=', $id)
					->having('article:type:alias', '=', 'video')
					->find_all();
			}

			if ($user->service_notepad) {
				$this->template->favorites_notepad = ORM::factory('favorite')
					->where('favorites.user_id', '=', $id)
					->having('article:type:alias', 'NOT IN', array('video', 'photo'))
					->having('article:section:id', 'NOT IN', ORM::factory('section')->get_childs('house/cook'))
					->find_all();
			}

			$this->template->topics = Model::factory('forum')->lasts_topic_by_user_id($id);
		}

		$this->template->bind('user', $user);
	}

	public function action_reset()
	{
		$this->template = View::factory('user/reset');

		$token = $this->request->param('token');
		$first_stage = ($token === NULL);

		if ($this->request->method() == 'POST') {
		    if ($first_stage) {
			$email = $this->request->post('email');
			$model = ORM::factory('user', array('user_email' => $email));

			if ($model->loaded()) {
			    $confirm = ORM::factory('confirm');
			    $confirm->code = md5(microtime(TRUE).uniqid());
			    $confirm->user = $model;

			    $confirm->save();

			    $data = array(
				'email' => $model->user_email,
				'code' => $confirm->code,
    			    );
    			    Queue::instance()->add('reset', $data);
    			    $this->template->success = array('На указанную Вами почту было отправлено письмо со ссылкой на сброс пароля');
    			} else {
    			    $this->template->errors = array('Пользователь с такой почтой не зарегистрирован на сайте');
    			}
		    } else {
				$code = $this->request->param('token');
				$code = ORM::factory('confirm', $code);
				if ($code->loaded()) {
				    $post = $this->request->post();
				    try {
						$code->user->update_user($post, array('password'));
						$code->delete();

						$this->request->redirect('/login/');
				    } catch (ORM_Validation_Exception $e) {
						$this->template->errors = $e->errors('validation');
				    }
				} else {
				    $this->template->errors = array('Неверный код подтверждения');
				}
		    }
		}

		$this->template->first_stage = $first_stage;
	}

	public function action_token()
	{
	    $token = NULL;

	    if ($user = Auth::instance()->get_user()) {
			$user_agent = sha1(Request::$user_agent);
			$data = array(
			    'user_id' => $user->id,
			    'user_agent' => $user_agent,
			    'type' => 'forum',
			);

			$token = ORM::factory('user_token', $data);
			if ( ! $token->loaded()) {
			    // Token data
				$data['expires'] = time() + 300;

			    // Create a new autologin token
			    $token = ORM::factory('user_token')
					->values($data)
					->create();
			}
			$token = $token->token;

	    }

	    if ($this->request->param('type', 'json') == 'json') {
			$token = json_encode(array('kohana_token' => $token));
	    } else {
			$token = 'var __kohana_token = "'.$token.'";';
	    }
	    $this->response->body($token);
	}
}
