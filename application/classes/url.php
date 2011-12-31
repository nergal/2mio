<?php defined('SYSPATH') or die('No direct script access.');

/**
 * @author nergal
 */
class URL extends Kohana_URL {
	/**
	 * Fetches an absolute site URL based on a URI segment.
	 *
	 *     echo URL::site('foo/bar');
	 *
	 * @param   string  $uri        Site URI to convert
	 * @param   mixed   $protocol   Protocol string or [Request] class to use protocol from
	 * @param   boolean $index      Include the index_page in the URL
	 * @return  string
	 * @uses    URL::base
	 */
	public static function site($uri = '', $protocol = NULL, $index = TRUE)
	{
	    $url = parent::site($uri, $protocol, $index);
	    if (substr($url, -1, 1) != '/' AND (! strpos($url, '.'))) { // OR $protocol == TRUE)) {
		$url.= '/';
	    }
	    
	    return $url;
	}
}
