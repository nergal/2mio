<?php defined('SYSPATH') or die('No direct access allowed.');

abstract class SSO_Core_Service_Vkontakte extends SSO_OAuth2 {

	/**
	 * @var  string  sso service name
	 */
	protected $sso_service = 'vkontakte';

	/**
	 * @var  object  Facebook SDK
	 */
	protected $vk;

	public function __construct()
	{
		// Include Facebook SDK
		include Kohana::find_file('vendor', 'vkontakte/api');

		// Set config		
		$config = Kohana::$config->load('oauth.vkontakte');
		$sso = Kohana::$config->load('sso.vkontakte');
		
		$callback = URL::site($sso['callback'], Request::current());

		// Setup Vkontakte
		$this->vk = new Vkontakte(
			array(
				'app_id'     => $config['key'],
				'secret'     => $config['secret'],
				'save_token' => TRUE,
				'endpoint'   => $callback,
				'connect'    => TRUE,
			)
		);
		
		parent::__construct();
	}

	/**
	 * Attempt to log in a user by using an OAuth provider.
	 *
	 * @return  boolean
	 * @uses    Request::current()
	 */
	public function login()
	{
		if ($session = Request::current()->query('code')) {
			// Set session
			$session = json_decode(get_magic_quotes_gpc() ? stripslashes($session) : $session, TRUE);

			try {
			    // Load session
			    $this->vk->setSession($session);
			} catch (Vkontakte_Exception $e) {
			    Kohana::$log->add(Log::ERROR, $e->getMessage());
			}

			// Complete login
			return $this->complete_login($this->sso_service);
		} elseif ($_GET AND ! Request::current()->query('code')) {
			// User denied the access to his / her account
			return FALSE;
		}

		// Redirect to provider's login page and ask for e-mail permission
		Request::current()->redirect($this->vk->getAuthUrl());
	}

} // End SSO_Core_Service_Facebook