<?php
/**
 * This is a class that is used to parse a string and format it
 * for BBCode. This class implements the use of a unique
 * identifier, for the purpose of saving resources, post-database.
 *
 * @author        Matt Carroll <admin@develogix.com>
 * @copyright     Copyright 2004, 2005, 2006, 2007, 2008, 2009, 2010, Matt Carroll
 *                http://gnu.org/copyleft/gpl.html GNU GPL
 * @version       $Id: bbcode.class.php,v 3.0.2 2010/11/12 19:10:00 logi Exp $
 *
 * This version updates the class to PHP5, as well as implementing a new method of parsing.
 *
 * private @param str       string to be parsed
 * public  @param uid       unique identifier with a length of 8 characters
 * private @param action    'pre' or 'post' database
 *
 * private @param added     array holding added simple tags and data 
 *
 * private @param geshi     true to allow bbGeshi, false to disallow
 * private @param ls        true to allow bbList, false to disallow
 * private @param simple    true to allow bbSimple, false to disallow
 * private @param quote     true to allow bbQuote, false to disallow
 * private @param mail      true to allow bbMail, false to disallow
 * private @param url       true to allow bbUrl, false to disallow
 * private @param img       true to allow bbImg, false to disallow
 *   
 * private @param imgLimit  amount of images to be parsed (-1 for unlimited)
 */
 
require_once 'bbcode.interface.php';
 
class BBcode
{
	protected $str       = "";
	public    $uid       = NULL;
	protected $action    = NULL;

	protected $added     = array();

	protected $geshi     = TRUE;
	protected $ls        = TRUE;
	protected $simple    = TRUE;
	protected $quote     = TRUE;
	protected $mail      = TRUE;
	protected $url       = TRUE;
	protected $img       = TRUE;

	protected $img_limit = -1;

	public function __construct()
	{

	}
	
	public function factory($name = NULL)
	{
	    if ($name !== NULL) {
		$file_name = 'drivers/'.$name;
		if (file_exists($file_name)) {
		    require_once $file_name;
		    
		    $class_name = 'BBCode_'.ucfirst($name);
		    $instance = new $class_name;
		    
		    return $instance;
		}
	    }
	    
	    return NULL;
	}

	/**
	 * @param  str	string to be parsed
	 * @param  action 'pre' or 'post' database, null for standard parse
	 * @param  uid	unique identifier with a length of 8 characters, null for standard parse
	 *
	 * @return parsed string
	 */
	public function parse($str, $action = NULL, $uid = NULL)
	{
		$this->str    = $str;
		$this->action = $action;

		if ($uid === NULL AND $this->action === 'pre' OR $this->action === NULL) {
			$this->uid = $this->_make_uid();
		} else {
			$this->uid = ($this->action === 'post' AND (strlen($uid) === 8)) ? $uid : NULL;
		}

		if ($this->action === 'pre') {
			$this->_bb_list();
			$this->_bb_simple();
			$this->_bb_quote();
			$this->_bb_mail();
			$this->_bb_url();
			$this->_bb_img();

			return $this->str;
		} elseif ($this->action === 'post' OR $this->action === NULL) {
			$this->_bb_list();
			$this->_bb_simple();
			$this->_bb_quote();
			$this->_bb_mail();
			$this->_bb_url();
			$this->_bb_img();

			$this->str = '<p>'.$this->str.'</p>'."\n";

			$match = array(
				'#\r\n\r\n#msi',
				'#(?<!(</div>))\r\n#msi',
				'#(<(/)?p>)?<(/)?(div( class="(.*?)")?|ul|ol|li|h[1-6])>(<(/)?p>)?#msi',
				'#\n</p>#m',
				'#<ul>(.*?)</p>#msi'
			);
			$replace = array(
				"\r\n",
				'</p>'."\r\n".'<p>',
				'<$3$4>',
				'',
				'<ul>$1'
			);
				
			$this->str = preg_replace($match, $replace, $this->str);

			return substr($this->str, 0, strlen($this->str) - 1);
		} else return NULL;
	}

	/**
	 * adds a "simple" bbcode tag
	 * please ensure that new lines are "\r\n"
	 * @param tag    opening and closing bbcode tag
	 * @param before text that goes in place of [$tag]
	 * @param after  text that goes in place of [/$tag]
	 */
	public function add_simple($tag, $before, $after, $tabs)
	{
		$this->added[] = array($tag, $before, $after, $tabs);
	}

	/**
	 * @param var function name to disallow
	 */
	public function disallow($var)
	{
		$this->{$var} = FALSE;
	}

	/**
	 * @param var function name to allow
	 */
	public function allow($var)
	{
		$this->{$var} = TRUE;
	}

	/**
	 * sets image limit
	 * @param limit amount of images to be parsed (-1 for unlimited)
	 */
	public function img_limit($limit)
	{
		$this->img_limit = $limit;
	}

	/**
	 * @return uid generated unique identifier with a length of 8 characters
	 */
	private function _make_uid()
	{
		return substr(md5(mt_rand()), 0, 8);
	}

	/**
	 * parses string for [list], [*]
	 */
	private function _bb_list()
	{
		if ($this->ls === TRUE) {
			if ($this->action === 'pre' OR $this->action === NULL) {
				$match = array(
					'#\[list\](.*?)\[/list\]#si',
					'#\[\*\](.*?)\[/\*\]#si'
				);
				$replace = array(
					'[list:'.$this->uid.']$1[/list:'.$this->uid.']',
					'[*:'.$this->uid.']$1[/*:'.$this->uid.']'
				);
				$this->str = preg_replace($match, $replace, $this->str);
			}

			if ($this->action === 'post' OR $this->action === NULL) {
				$match = array(
					'[list:'.$this->uid.']', '[/list:'.$this->uid.']',
					'[*:'.$this->uid.']', '[/*:'.$this->uid.']'
				);
				$replace = array(
					'<ul>', '</ul>',
					'<li>', '</li>'
				);
				$this->str = str_replace($match, $replace, $this->str);
			}
		}
	}

	/**
	 * parses string for [b], [i], [u], [s], [em], [sup], and [sub]
	 */
	private function _bb_simple()
	{
		if ($this->simple === TRUE) {
			if ($this->action === 'pre' OR $this->action === NULL) {
				$match = array(
					'#\[b\](.*?)\[/b\]#si',
					'#\[i\](.*?)\[/i\]#si',
					'#\[u\](.*?)\[/u\]#si',
					'#\[s\](.*?)\[/s\]#si',
					'#\[em\](.*?)\[/em\]#si',
					'#\[sup\](.*?)\[/sup\]#si',
					'#\[sub\](.*?)\[/sub\]#si'
				);
				$replace = array(
					'[b:'.$this->uid.']$1[/b:'.$this->uid.']',
					'[i:'.$this->uid.']$1[/i:'.$this->uid.']',
					'[u:'.$this->uid.']$1[/u:'.$this->uid.']',
					'[s:'.$this->uid.']$1[/s:'.$this->uid.']',
					'[em:'.$this->uid.']$1[/em:'.$this->uid.']',
					'[sup:'.$this->uid.']$1[/sup:'.$this->uid.']',
					'[sub:'.$this->uid.']$1[/sub:'.$this->uid.']'
				);
				foreach ($this->added AS $arr) {
					$match[]   = '#\['.$arr[0].'\](.*?)\[/'.$arr[0].'\]#si';
					$replace[] = '['.$arr[0].':'.$this->uid.']$1[/'.$arr[0].':'.$this->uid.']';
				}
				$this->str = preg_replace($match, $replace, $this->str);
			}

			if ($this->action === 'post' OR $this->action === NULL) {
				$match = array(
					'[b:'.$this->uid.']', '[/b:'.$this->uid.']',
					'[i:'.$this->uid.']', '[/i:'.$this->uid.']',
					'[u:'.$this->uid.']', '[/u:'.$this->uid.']',
					'[s:'.$this->uid.']', '[/s:'.$this->uid.']',
					'[em:'.$this->uid.']', '[/em:'.$this->uid.']',
					'[sup:'.$this->uid.']', '[/sup:'.$this->uid.']',
					'[sub:'.$this->uid.']', '[/sub:'.$this->uid.']'
				);
				$replace = array(
					'<strong>', '</strong>',
					'<em>', '</em>',
					'<span style="text-decoration: underline;">', '</span>',
					'<del>', '</del>',
					'<em>', '</em>',
					'<sup>', '</sup>',
					'<sub>', '</sub>'
				);
				foreach ($this->added as $arr) {
					$match[]   = '['.$arr[0].':'.$this->uid.']';
					$replace[] = $arr[1];
					$match[]   = '[/'.$arr[0].':'.$this->uid.']';
					$replace[] = $arr[2];
				}
				$this->str = str_replace($match, $replace, $this->str);
			}
		}
	}

	/**
	 * parses string for [quote=*] and [quote]
	 */
	private function _bb_quote()
	{
		if ($this->quote === TRUE) {
			if ($this->action === 'pre' OR $this->action === NULL) {
				$match = array(
					'#\[quote="(.*?)"\](.*?)\[/quote\]#si',
					'#\[quote\](.*?)\[/quote\]#si'
				);
				$replace = array(
					'[quote="$1":'.$this->uid.']$2[/quote:'.$this->uid.']',
					'[quote:'.$this->uid.']$1[/quote:'.$this->uid.']'
				);
				$this->str = preg_replace($match, $replace, $this->str);
			}

			if ($this->action === 'post' OR $this->action === NULL) {
				$match = array(
					'#\[quote="([^"]*)":'.$this->uid.'\](.*?)\[/quote:'.$this->uid.'\]#si',
					'#\[quote:'.$this->uid.'\](.*?)\[/quote:'.$this->uid.'\]#si'
				);
				$replace = array(
					'<blockquote><div><strong>Quoted from <em>$1</em></strong><br />$2</div></blockquote>',
					'<blockquote><div><strong>Quote</strong><br />$1</div></blockquote>'
				);
				$replace = array(
					'<div class="citata"><div class="text_cit"><p><a class="usernick">$1</a></p><p>$2</p></div></div>',
					'<div class="citata"><div class="text_cit"><p>$1</p></div></div>',
				);
				$this->str = preg_replace($match, $replace, $this->str);
			}
		}
	}


	/**
	 * parses string for [mail=*] and [mail]
	 */
	private function _bb_mail()
	{
		if ($this->mail === TRUE) {
			if ($this->action === 'pre' OR $this->action === NULL) {
				$match = array(
					'#\[mail=([a-z0-9\-_.]+?@[\w\-]+\.([\w\-\.]+\.)?[\w]+)\](.*?)\[/mail\]#si',
					'#\[mail\]([a-z0-9\-_.]+?@[\w\-]+\.([\w\-\.]+\.)?[\w]+)\[/mail\]#si'
				);
				$replace = array(
					'[mail=$1:'.$this->uid.']$2[/mail:'.$this->uid.']',
					'[mail=$1:'.$this->uid.']$1[/mail:'.$this->uid.']'
				);
				$this->str = preg_replace($match, $replace, $this->str);
			}

			if ($this->action === 'post' OR $this->action === NULL) {
				$match = '#\[mail=([a-z0-9\-_.]+?@[\w\-]+\.([\w\-\.]+\.)?[\w]+):'.$this->uid.'\](.*?)\[/quote\]#si';
				$replace = '<a href="mailto:$1">$2</a>';
				$this->str = preg_replace($match, $replace, $this->str);
			}
		}
	}

	/**
	 * parses string for [url=*], [url], and unformatted URLs
	 */
	private function _bb_url()
	{
		if ($this->url === TRUE) {
			if ($this->action === 'pre' OR $this->action === NULL) {
				$match= array(
					'#(?<!(\]|=|\/))((http|https|ftp|irc|telnet|gopher|afs)\:\/\/)(.+?)( |\n|\r|\t|\[|$)#si',
					'#(?<!(\]|=|\/))((www|ftp)\.)(.+?)( |\n|\r|\t|\[|$)#si',
					'#\[url\]([a-z0-9]+?://){1}([\w\-]+\.([\w\-]+\.)*[\w]+(:[0-9]+)?(/[^ \"\n\r\t<]*)?)\[/url\]#is',
					'#\[url\]((www|ftp)\.([\w\-]+\.)*[\w]+(:[0-9]+)?(/[^ \"\n\r\t<]*?)?)\[/url\]#si',
					'#\[url=([a-z0-9]+://)([\w\-]+\.([\w\-]+\.)*[\w]+(:[0-9]+)?(/[^ \"\n\r\t<]*?)?)\](.*?)\[/url\]#si',
					'#\[url=(([\w\-]+\.)*?[\w]+(:[0-9]+)?(/[^ \"\n\r\t<]*)?)\](.*?)\[/url\]#si'
				);
				$replace   = array(
					'[url:'.$this->uid.']$1$2$4[/url:'.$this->uid.']$5',
					'[url:'.$this->uid.']http://$1$2$4[/url:'.$this->uid.']$5',
					'[url:'.$this->uid.']$1$2[/url:'.$this->uid.']',
					'[url:'.$this->uid.']http://$1[/url:'.$this->uid.']',
					'[url=$1$2:'.$this->uid.']$6[/url:'.$this->uid.']',
					'[url=http://$1:'.$this->uid.']$5[/url:'.$this->uid.']'
				);
				$this->str = preg_replace($match, $replace, $this->str);
			}

			if ($this->action === 'post' OR $this->action === NULL) {
				$match = array(
					'#\[url:'.$this->uid.'\](.*?)\[/url:'.$this->uid.'\]#si',
					'#\[url=(.*?):'.$this->uid.'\](.*?)\[/url:'.$this->uid.'\]#si'
				);
				$replace = array('<a href="$1">$1</a>', '<a href="$1">$2</a>');
				$this->str = preg_replace($match, $replace, $this->str);

				if ( ! function_exists('_bb_url2')) {
					function _bb_url2($matches)
					{
						return '<a href="'.str_replace('&', '&amp;', str_replace('&amp;', '&', $matches[1])).'">';
					}
				}
				$this->str = preg_replace_callback('#<a href="(.*?)">#si', '_bb_url2', $this->str);
			}
		}
	}

	/**
	 * parses string for [img], limited to $this->img_limit amount of times
	 */
	private function _bb_img()
	{
		if ($this->img === TRUE OR $this->action === NULL) {
			if ($this->action === 'pre') {
				$match = '#\[img\](.*?)\[\/img\]#si';
				$replace = '[img:'.$this->uid.']$1[/img:'.$this->uid.']';
				$this->str = preg_replace($match, $replace, $this->str, $this->img_limit);
			}

			if ($this->action === 'post' OR $this->action === NULL) {
				$match     = '#\[img:'.$this->uid.'\](.*?)\[/img:'.$this->uid.'\]#si';
				$replace   = '<img src="$1" />';
				$this->str = preg_replace($match, $replace, $this->str, $this->img_limit);
			}   
		}
	}
}

