<?php

/**
 * Vkontakte.ru API
 *
 * @see http://vkontakte.ru/developers.php
 * @package Vkontakte
 * @author nergal
 */
class Vkontakte
{
    /**
     * Адреса API
     * @var string $url
     * @var string $oauth_url
     */
    protected $url       = 'https://api.vkontakte.ru/method/';
    protected $oauth_url = 'https://api.vk.com/oauth/';

    /**
     * Токен
     * @var string
     */
    private $access_token = NULL;

    /**
     * Настройки
     * @var array
     */
    protected $_config = array(
		'app_id'      => NULL,
		'secret'      => NULL,
		'save_token'  => TRUE,
		'cookie_name' => 'oauth_token',
		'timeout'     => 60,
		'domain'      => '.example.com',
    	'endpoint'    => 'http://www.example.com/oauth/',
    	'debug'       => FALSE,
    	'connect'     => FALSE,
    );

    /**
     * Получение токена
     *
     * @throws Vkontakte_Exception
     * @param array $config
     */
    public function __construct(Array $config = array())
    {
		$this->_config = array_merge($this->_config, $config);

		if ( ! $this->getSession()) {
		    if ($this->_config['connect'] AND ( ! $this->connect())) {
				throw new Vkontakte_Exception('Can\'t connect');
		    }
		}
    }

    /**
     * Соединение
     *
     * @return boolean
     */
    public function connect()
    {
        if (isset($_GET['code'])) {
    	    $access_code = $_GET['code'];
        } else {
    	    $this->getCode();
        }

		$this->getToken($access_code);
		return ($this->access_token !== NULL);
    }

    /**
     * Получение токена
     *
     * @see http://vkontakte.ru/developers.php?o=-1&p=%C0%E2%F2%EE%F0%E8%E7%E0%F6%E8%FF%20%F1%E0%E9%F2%EE%E2
     * @param string $code
     * @return void
     */
    protected function getToken($code)
    {
		$params = array(
		    'client_id' => $this->_config['app_id'],
		    'client_secret' => $this->_config['secret'],
		    'code' => $code,
		);
		$response = (array) $this->request('access_token', $params, $this->oauth_url);
		if (isset($response['access_token'])) {
		    $this->access_token = $response['access_token'];
		    $this->user_id = $response['user_id'];

		    $this->setSession($response);
		}
    }

    public function setSession($session = NULL, $write_cookie = TRUE)
    {
		$this->validateSession($session);

		$timeout = time() + intVal($session['expires_in']);

		$this->access_token = $session['access_token'];
		$this->user_id = $session['user_id'];

		if ($write_cookie OR $this->_config['save_token']) {
			$data = base64_encode(implode(':', $session));
			setcookie($this->_config['cookie_name'], $data, $timeout, '/', $this->_config['domain']);
		}
    }

    public function getSession()
    {
    	if (isset($_COOKIE[$this->_config['cookie_name']])) {
    		$session = $_COOKIE[$this->_config['cookie_name']];
			$session = base64_decode(explode(':', $session));

			$this->validateSession($session);
			$this->setSession($session, FALSE);

			return TRUE;
    	}

    	return FALSE;
    }

    public function validateSession($session)
    {
    	$allowed = array('access_token', 'expires_in', 'user_id');

    	$session = (array) $session;

    	if (count($session) != 3) {
    		throw new Vkontakte_Exception('Not valid session');
    	}

    	foreach ($allowed as $item) {
    		if (!isset($session[$item])) {
    			throw new Vkontakte_Exception('Not valid session');
    		}
    	}
    }

    /**
     * Переадресация для получения разрешения
     *
     * @see http://vkontakte.ru/developers.php?o=-1&p=%C4%E8%E0%EB%EE%E3%20%E0%E2%F2%EE%F0%E8%E7%E0%F6%E8%E8%20OAuth
     * @return void
     */
    protected function getCode()
    {
		$url = $this->getAuthUrl();

		header('Location: '.$url);
		die();
    }

    /**
     * Формирования урла для запроса прав
     *
     * @return string
     */
    public function getAuthUrl()
    {
		$params = array(
		    'client_id' => $this->_config['app_id'],
		    'redirect_uri' => $this->_config['endpoint'],
		    'scope' => 1,
		    'response_type' => 'code',
			'display' => 'page',
		);
		$url = $this->buildURI('authorize', $params, $this->oauth_url);

		return $url;
    }

    /**
     * Фетч профиля
     *
     * @see http://vkontakte.ru/developers.php?o=-1&p=getProfiles
     * @param integer $user_id
     * @return mixed
     */
    public function getProfile($user_id = NULL, Array $fields = array())
    {
    	if ($user_id === NULL) {
    		$user_id = $this->user_id;
    	}

    	if (empty($fields)) {
    		$fields = array(
    			'uid',
    			'first_name',
    			'last_name',
    			'nickname',
    			'domain',
    			'sex',
    			'bdate',
    			'city',
    			'country',
    			'timezone',
    			'photo_big',
    			'online',
    		);
    	}
		$result = $this->request('getProfiles', array('uid' => intVal($user_id), 'fields' => implode(',', $fields)));
		if ( ! empty($result)) {
			return $result[0];
		}

		return NULL;
    }

    /**
     * Построение урла
     *
     * @param string $method
     * @param array $params
     * @param string $url
     * @return string
     */
    protected function buildURI($method, Array $params = array(), $url = NULL)
    {
		if ( ! empty($this->access_token)) {
		    $params['access_token'] = $this->access_token;
		}

		if ($url === NULL) {
		    $url = $this->url;
		}

		$url = array(
		    $url.$method,
		    http_build_query($params),
		);
		$url = implode('?', $url);
		return $url;
    }

    /**
     * Базовый метода запроса
     *
     * @throws Vkontakte_Exception
     * @param string $method
     * @param array $params
     * @param string $url
     * @return mixed
     */
    protected function request($method, Array $params = array(), $url = NULL)
    {
    	$_raw_response = !($url == NULL);
		$url = $this->buildURI($method, $params, $url);

		$curl = curl_init();
		$options = array(
		    CURLOPT_URL => $url,
		    CURLOPT_HEADER => FALSE,
		    CURLOPT_RETURNTRANSFER => TRUE,
		    CURLOPT_TIMEOUT => $this->_config['timeout'],
		    CURLOPT_SSL_VERIFYPEER => FALSE,
		    CURLOPT_SSL_VERIFYHOST => FALSE,
		);
		curl_setopt_array($curl, $options);
		$data = curl_exec($curl);

		curl_close($curl);

		$result = json_decode($data, TRUE);

		if ($this->_config['debug']) {
			ob_start();
			var_dump($result);
			$response = ob_get_clean();

			echo "<table border='1' cellpadding='5' width='100%'>";
			echo "<tr><td>Request =></td><td>{$url}</td></tr>";
			echo "<tr><td>Response <=</td><td>{$response}</td></tr>";
			echo "</table>";
		}

		if (isset($result['error'])) {
			if (isset($result['error_description'])) {
				throw new Vkontakte_Exception($result['error_description']);
			}

			if ( ! is_string($result['error'])) {
				$error = $result['error'];
				throw new Vkontakte_Exception($error['error_msg'], $error['error_code']);
			}
		}

		if ($_raw_response === TRUE AND isset($result['response'])) {
			throw new Vkontakte_Exception('Request failed');
		}

		if ($_raw_response !== TRUE) {
			if (isset($result['response'])) {
				return $result['response'];
			}
		}

		return $result;
    }
}

/**
 * Перегрузка исключения
 *
 * @package Vkontakte
 * @author nergal
 */
class Vkontakte_Exception extends Exception {}
