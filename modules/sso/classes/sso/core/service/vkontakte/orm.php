<?php defined('SYSPATH') or die('No direct access allowed.');

class SSO_Core_Service_Vkontakte_ORM extends SSO_Service_Vkontakte {

	/**
	 * Completes the login and signs up a user if necessary.
	 *
	 * @return  boolean
	 * @uses    Kohana::$log
	 * @uses    Log::ERROR
	 * @uses    Kohana_Exception::text
	 * @uses    ORM::factory
	 * @uses    Auth::instance
	 */
	protected function complete_login()
	{
		try {
			// Get user details
			$data = $this->vk->getProfile();
			$data = $this->_normalize($data);
		} catch (Vkontakte_Exception $e) {
			// Log the error and return FALSE
			Kohana::$log->add(Log::ERROR, Kohana_Exception::text($e));
			return FALSE;
		}

		// Set provider field
		$provider_field = $this->sso_service.'_id';

		// Check whether that id exists in our users table (provider id field)
		$user = ORM::factory('user')->find_sso_user($provider_field, $data);

		// Signup if necessary
		$signup = ORM::factory('user')->sso_signup($user, $data, $provider_field);

		// Give the user a normal login session
		Auth::instance()->force_sso_login($signup);

		return TRUE;
	}
	
	private function _normalize($data)
	{
	    return array_merge($data, array('id' => $data['uid']));
	}

} // End SSO_Core_Service_Facebook_ORM