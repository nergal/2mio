<?php

class Helper
{
	const TITLE   	= 1;
	const POST    	= 2;
	const EXTREME 	= 4;
	const REQUEST 	= 8;
	const BODY    	= 16;
	const RSS_TITLE = 32;
	const RSS_BODY  = 64;
	const COMMENT	= 128;

	/**
	 * Hashes an email address to a big integer
	 *
	 * @param string $email		Email address
	 *
	 * @return string			Unsigned Big Integer
	 */
	public static function phpbb_email_hash($email)
	{
		return sprintf('%u', crc32(strtolower($email))).strlen($email);
	}

	/**
	 * Return unique id
	 *
	 * @param string $extra additional entropy
	 */
	public static function unique_id($extra = 'c')
	{
		// Это нам не нужно, поскольку у нас свои конфиги и сидирование
		// static $dss_seeded = false;
		// global $config;

		// Берём нашу соль, вместо сохранённой в конфигах
		// $val = $config['rand_seed'] . microtime();
		$val = Cookie::$salt.microtime();
		$val = md5($val);

		// Некуда сохранять конфиг - у нас разные сессии, да и для сайта это не нужно
		// $config['rand_seed'] = md5($config['rand_seed'] . $val . $extra);

		/*
		// такого у нас тоже нет, форум по логике должен сам пересоздать сид при входе
		if ($dss_seeded !== true && ($config['rand_seed_last_update'] < time() - rand(1,10)))
		{
			set_config('rand_seed_last_update', time(), true);
			set_config('rand_seed', $config['rand_seed'], true);
			$dss_seeded = true;
		}
		*/

		return substr($val, 4, 16);
	}


	/**
	 * Фильтрация содержимого
	 *
	 * @param string $body
	 * @param integer $type
	 * @return string
	 */
	public static function filter($body, $type = Helper::TITLE)
	{
		$filters = (array) Helper::get_filters_for($type);

		foreach ($filters as $callback) {
			if (is_callable($callback)) {
				$body = call_user_func($callback, $body);
			}
		}

		return $body;
	}

	/**
	 * Выборка списка фильтров
	 *
	 * @param integer $type
	 * @return array
	 */
	protected static function get_filters_for($type)
	{
		$filters = array();
		if ($type & Helper::TITLE) {
			$filters['stripTags'] = 'strip_tags';
			$filters['light_escape'] = array('Helper', 'light_escape');
			$filters['xss'] = 'un_xss';
		}

		if ($type & Helper::RSS_TITLE) {
			$filters['stripTags'] = 'strip_tags';
			$filters['xss'] = 'un_xss';
			$filters['rss_title'] = array('Helper', 'clear_rss_title');
			$filters['rss_html'] = array('Helper', 'clear_rss_html');
		}

		if ($type & Helper::RSS_BODY) {
			$filters['stripTags'] = 'strip_tags';
			$filters['xss'] = 'un_xss';
			$filters['rss_html'] = array('Helper', 'clear_rss_html');
		}

		if ($type & Helper::POST) {
			$filters['stripTags'] = array('Helper', 'strip_tag');
			$filters['escape'] = array('Helper', 'escape');
			// TODO: переписать эту гадость
			// $filters['bbcode'] = 'bbcode_parse';
			$filters['xss'] = 'un_xss';
			$filters['auto_p'] = array('Text', 'auto_p');
			$filters['normalize'] = array('Helper', 'normalize');
			$filters['slashes'] = array('Helper', 'strip_slashes');
		}

		if ($type & Helper::REQUEST) {
			$filters['stripTags'] = array('Helper', 'strip_tag');
			$filters['escape'] = array('Helper', 'escape');
		}

		if ($type & Helper::EXTREME) {
			$filters['xss'] = array('Security', 'xss_clean');
		}

		if ($type & Helper::BODY) {
			$filters['stripTags'] = array('Helper', 'strip_tags_article');
		}

		if ($type & Helper::COMMENT) {
			$filters['stripTags'] = array('Helper', 'strip_tags_article');
			$filters['censored'] = array('Helper', 'unti_fuck');
			$filters['stripConcurent'] = array('Helper', 'unti_concurent');
            $filters['bbcode'] = 'bbcode_parse';
		}

		return $filters;
	}

	public static function clear_rss_title($var)
	{
		$var = preg_replace('/\.*\s+(ФОТО|ВИДЕО)\s*\.*\s*$/i', '', $var);
		return $var;
	}

	public static function clear_rss_html($var)
	{
		$var = html_entity_decode($var, ENT_COMPAT, 'UTF-8');
		return $var;
	}

    /**
     * Escapes a value for output in a view script.
     *
     * @param mixed $var The output to escape.
     * @return mixed The escaped value.
     */
    public static function escape($var)
    {
        return call_user_func('htmlspecialchars', $var, ENT_COMPAT, Kohana::$charset);
    }

    /**
     *  вариант htmlspecialchars без обработки &
     *
     * @param mixed $var The output to escape.
     * @return mixed The escaped value.
     */
    public static function light_escape($var)
    {
		$var = preg_replace('/"/', '&quot;',  $var);
		$var = preg_replace('/>/', '&gt;',  $var);
		$var = preg_replace('/</', '&lt;',  $var);
		$var = preg_replace('/\'/', '&#039;',  $var);

		return $var;
	}

    /**
     *  чистим мат
     *
     * @param mixed $var The output to clear fuck.
     * @return mixed The censored value.
     */

	public static function unti_fuck($var)
	{
		include_once Kohana::find_file('vendor', 'censure/censure');
		include_once Kohana::find_file('vendor', 'censure/phputf8');
		return Censure::parse($var, 1, '', false, '[цензура]');
	}
	
    /**
     *  чистим конкурентов
     *
     * @param mixed $var The output to clear concurent.
     * @return mixed The free concurent value.
     */

	public static function unti_concurent($var)
	{
		$concurents = array(
			'lady.tochka.net',
			'ivona.bigmir.net',
			'cosmo.com.ua',
			'terrawoman.com',
			'woman.ru',
		);
		
		foreach ($concurents as $concurent) {
			$var = preg_replace('/\b\S*'.$concurent.'\S*/', '', $var);
		}
		
		return $var;
	}	

    /**
     * Очистка тегов старой базы
     *
     * @param string $var
     * @return string
     */
    public static function strip_tag($var)
    {
    	// br2nl
    	$break = "\n";
    	$var = preg_replace('#\<br( /)?\>#i', $break, $var);

    	return strip_tags($var);
    }

    public static function strip_tags_article($var)
    {
		$var = strip_tags($var, '<p><a><b><i><u><s><table><th><tr><td><thead><tbody><strong><span><br><img><div><iframe><object><embed>');
		$var = str_replace('<a ', '<a rel="nofollow" ', $var);

		return $var;
    }

    /**
     * Нормализация постов из старой базы
     *
     * @param string $var
     * @return string
     */
    public static function normalize($var)
    {
    	return str_replace('&amp;quot;', '&quot;', $var);
    }

    /**
     * Сделать ссылку
     *
     * @param array|string $params
     * @param string $sub_domain
     * @param boolean $is_slash
     * @param array|object $vars
     * @param string $protocol
     * @return string
     */
    public static function uri($params = array(), $sub = NULL, $is_www = true, $is_slash = true)
    {
    	$_host = $_SERVER['SERVER_NAME'];
    	$host = (preg_match('/\./', $_host) ? $_host : (($sub === NULL) ? ("www.{$_host}") : ("{$sub}.{$_host}")));

    	if ($sub == NULL) {
    		$host = ($is_www ? 'www.' : '').'likar.info';
    	}

    	$params = implode('/', (array) $params);

    	$url = "http://{$host}/{$params}";

    	if ( ! empty($vars)) {
    		foreach ($vars as $key => $value) {
    			$url.= "/{$key}/{$value}";
    		}
    	}

    	if ($is_slash === TRUE) {
    		$url = rtrim($url).'/';
    	}

    	return $url;
    }

    /**
     * Очистка слешей из старой базы
     *
     * @param string $var
     * @return string
     */
    public static function strip_slashes($str)
    {
    	return preg_replace('#\\\\{2,}#', ' ', $str);
    }

    /**
     * Генерация ссылки на пост
     *
     * @param integer $post_id
     * @param string $action
     * @return string
     */
    public static function post_uri($post_id, $action = NULL)
    {
    	$url = 'post_'.intVal($post_id).'.html';
    	if ($action !== NULL) {
    		$url.= '?action='.$action;
    	}

    	return self::uri(array($url), 'forum', TRUE, FALSE);
    }

    /**
     * Генерация ссылки на ветку обсуждения
     *
     * @param integer $thread_id
     * @param integer $message_id якорь сообщения
     */
	public static function thread_uri($thread_id, $message_id = NULL)
	{
    	$url = '/topic_'.intVal($thread_id).'.html';
    	if ($message_id !== NULL) {
    		$url.= '#post-'.intVal($message_id);
    	}

    	return self::uri(array($url), 'forum', TRUE, FALSE);
    }

    /**
     * Генерация ссылки на профиль пользователя
     *
     * @param integer $user_id
     * @return string
     */
    public static function profile_uri($user_id)
    {
    	return self::uri(array('user', intVal($user_id)));
    }

    /**
     * Рендеринс ссылки на профиль
     *
     * @todo выборка $user_name из базы
     * @param integer $user_id
     * @param string $user_name
     * @return string
     */
    public static function profile_link($user_id, $user_name)
    {
    	return '<a href="'.self::profile_uri($user_id).'">'.self::filter($user_name).'</a>';
    }

    /**
     * Фильтрация xss и незакрытых тегов
     *
     * @see http://code.google.com/p/markhtml/source/browse/markhtml.php
     * @param string $html_code
     * @param boolean $xhtml
     * @return string
     */
    public static function un_xss($html_code, $xhtml = TRUE)
    {
        // Одиночны теги
        $tags_closed = array('img', 'br', 'hr', 'param', 'input');
        // Запрещенные теги
        $tags = array('script', 'meta', 'link', 'style', 'iframe', 'frameset', 'frame', 'layer', 'xml');
        // Запрещенные атрибуты
        $tags_attr = array(
            'style' => '\(',
            '.*' => 's[\s]*c[\s]*r[\s]*i[\s]*p[\s]*t[\s]*:',
            '^on' => '',
            'src' => 'm[\s]*h[\s]*t[\s]*m[\s]*l[\s]*:'
        );

        $open_tags_stack = array();
        $code = false;
        $link = false;

        // Разбиваем полученный код на учатки простого текста и теги
        $seg = array();
        while (preg_match('/<[^<>]+>/siu', $html_code, $matches, PREG_OFFSET_CAPTURE)) {
            if ($matches[0][1]) {
            	$seg[] = array(
            		'seg_type' => 'text',
            		'value' => substr($html_code, 0, $matches[0][1])
            	);
            }
            $seg[] = array(
            	'seg_type' => 'tag',
            	'value' => $matches[0][0]
           	);
            $html_code = substr($html_code, $matches[0][1] + strlen($matches[0][0]));
        }
        if ($html_code != '') {
        	$seg[] = array(
        		'seg_type' => 'text',
        		'value' => $html_code
        	);
        }

        // Обрабатываем полученные участки
        for ($i = 0; $i < count($seg); $i++) {
            // Если участок является простым текстом экранируем в нем спец. символы HTML
            if ($seg[$i]['seg_type'] == 'text') {
                // Мягко убираем лишние &amp;
                $seg[$i]['value'] = preg_replace('/&amp;([a-z#0-9]+;)/ui', '&$1', htmlentities($seg[$i]['value'], ENT_QUOTES, 'UTF-8'));
                // Обрабатываем текст типографом
                if ( ! $code AND $this->typograf) {
                    // Не создаем ссылок внутри ссылки
                    $this->typograf->findUrls = ! $link;
                    $seg[$i]['value'] = $this->typograf->execute($seg[$i]['value'], TYPOGRAF_MODE_NAMES);
                }

            // Тег
            } elseif ($seg[$i]['seg_type'] == 'tag'){

                // Тип тега: открывающий/закрывающий, имя тега, строка атрибутов
                preg_match('#^<\s*(/)?\s*([a-z]+:)?([a-z0-9]+)(.*?)>$#siu', $seg[$i]['value'], $matches);
                if (count($matches) == 0) {
                    $seg[$i]['seg_type']='text';
                    $i--;
                    continue;
                } elseif ($matches[1]) {
                    $seg[$i]['tag_type']='close';
                } else {
                    $seg[$i]['tag_type']='open';
                }

                if ($seg[$i]['tag_type'] != 'text') {
                    $seg[$i]['tag_ns'] = $matches[2];
                    $seg[$i]['tag_name'] = $matches[3];
                    $seg[$i]['tag_name_lc'] = strtolower($matches[3]);
                }

                if (($seg[$i]['tag_name_lc']=='code') AND ($seg[$i]['tag_type']=='close')) {
                    $code = false;
                }
                if (($seg[$i]['tag_name_lc']=='a') AND ($seg[$i]['tag_type']=='close')) {
                    $link = false;
                }

                // Тег внутри <code> превращаем в текст
                if ($code) {
                    $seg[$i]['seg_type'] = 'text';
                    $i--;
                    continue;
                }

                // Открывающий тег
                if ($seg[$i]['tag_type'] == 'open') {


                    // Недопустимый тег показываем как текст
                    if (array_search($seg[$i]['tag_name_lc'], $tags) !== FALSE){
                        $seg[$i]['action'] = 'show';
                    } else {
						// Допустимый тег

                        if ($seg[$i]['tag_name_lc'] == 'code') {
                        	$code = true;
                        }
                        if ($seg[$i]['tag_name_lc'] == 'a') {
                        	$link = true;
                        }

                        // Если тег не одиночный, записываем его в стек открывающих тегов
                        if (array_search($seg[$i]['tag_name_lc'], $tags_closed) === FALSE) {
                            array_push($open_tags_stack, $seg[$i]['tag_ns'].$seg[$i]['tag_name']);
                        }
                    }

                    // Обработка атрибутов
                    preg_match_all('#([a-z]+:)?([a-z]+)(\s*=\s*[\"]\s*(.*?)\s*[\"])?(\s*=\s*[\']\s*(.*?)\s*[\'])?(=([^\s>]*))?#siu', $matches[4], $attr_m, PREG_SET_ORDER);
                    $attr = array();

                    foreach ($attr_m as $arr) {
                        $attr_ns = $arr[1];
                        $attr_key = $arr[2];
                        $attr_val = $arr[count($arr)-1];
                        $is_attr = TRUE;
                        if ( ! (isset($seg[$i]['action']) AND $seg[$i]['action'] == 'show')) {
                            // Поиск неправильных атрибутов
                            foreach ($tags_attr as $key => $val) {
                                if (preg_match('/'.$key.'/ui', $attr_key)) {
                                    if ($val == '' OR preg_match('/'.$val.'/ui', html_entity_decode($attr_val, ENT_QUOTES, 'UTF-8'))) {
                                        $is_attr = FALSE;
                                        break;
                                    }
                                }
                            }
                        }
                        if ($is_attr) {
                            $attr[$attr_ns.$attr_key] = $attr_val;
                        }
                    }
                    $seg[$i]['attr'] = $attr;

                } else { // Закрывающий тег
                    // Допустимый тег
                    if (array_search($seg[$i]['tag_name_lc'], $tags) === FALSE) {
                        if ($seg[$i]['tag_name_lc'] == 'code') {
                        	$code = FALSE;
                        }

                        if ($seg[$i]['tag_name_lc'] == 'a') {
                        	$link = FALSE;
                        }

                        // Стек открывающих тегов пуст
                        if (count($open_tags_stack) == 0) {
                            $seg[$i]['action'] = 'del';
                        } else {

                            // Закрывающий тег не соответствует открывающему, добавляем закрывающий
                            $tn = array_pop($open_tags_stack);
                            if ($seg[$i]['tag_ns'].$seg[$i]['tag_name'] != $tn) {
                                array_splice($seg, $i, 0, array(
                                	array(
                                		'seg_type' => 'tag',
                                		'tag_type' => 'close',
                                		'tag_name' => $tn,
                                		'action' => 'add',
                                	)
                                ));
                            }
                        }
                    } else {
                    	// Недопустимый закрывающий тег, удаляем лишний, показываем запрещенный
                        $seg[$i]['action'] = (array_search($seg[$i]['tag_name_lc'], $tags_closed) !== FALSE) ? 'del' : 'show';
                    }
                }
            }
        }

        // Закрываем оставшиеся в стеке теги
        foreach (array_reverse($open_tags_stack) as $value) {
            array_push($seg, array(
            	'seg_type' => 'tag',
            	'tag_type' => 'close',
            	'tag_name' => $value,
            	'action' => 'add'
           	));
        }

        // Собираем профильтрованный код и возвращаем его
        $filtered_html = '';
        foreach ($seg as $segment) {
            if ($segment['seg_type'] == 'text') {
            	$filtered_html .= $segment['value'];
            } elseif (($segment['seg_type'] == 'tag') AND ! (isset($segment['action']) AND $segment['action'] == 'del')) {
                // Тег будет показан, или выведен как был
                if ((isset($segment['action']) AND $segment['action'] == 'show')) {
                    $st = '&lt;';
                    $et = '&gt;';
                } else {
                    $st = '<';
                    $et = '>';
                }
                // Открывающий тег
                if ($segment['tag_type'] == 'open') {
                    $filtered_html.= $st.$segment['tag_ns'].$segment['tag_name'];
                    if (isset($segment['attr']) AND is_array($segment['attr'])) {
                        foreach ($segment['attr'] as $attr_key=>$attr_val) {
                            // Убираем лишние &amp;
                            $attr_val = preg_replace('/&amp;([a-z#0-9]+;)/ui', '&$1', htmlentities($attr_val, ENT_NOQUOTES, 'UTF-8'));
                            $filtered_html.= ' '.$attr_key.(($xhtml or $attr_key != $attr_val) ? ('="'.$attr_val.'"') : '');
                        }
                    }
                    // Закрыть одиночный тег
                    if ($xhtml AND array_search($segment['tag_name'], $tags_closed) !== FALSE) {
                    	$filtered_html.= " /";
                    }
                    $filtered_html.= $et;
                } elseif ($segment['tag_type'] == 'close') { // Закрывающий тег
                    $filtered_html.= $st.'/'.(isset($segment['tag_ns'])?$segment['tag_ns']:'').$segment['tag_name'].$et;
                }
            }
        }
        return $filtered_html;
    }

    public static function plural($number, $form1, $form2, $form3)
    {
	$number = intVal($number);
	$number = abs($number);

	$form = $form3;
        if ($number % 10 == 1 AND $number % 100 != 11) {
    	    $form = $form1;
        } elseif ($number % 10 >= 2 AND $number % 10 <= 4 AND ($number % 100 < 10 OR $number % 100 >= 20)) {
    	    $form = $form2;
        }

	return $form;
    }

    public static function kilo_digit($number)
    {
		$k = '';
		if ($number > 999) {
			$k = 'K';
			$number = round($number / 1000);
		}

		$number = Helper::space_digit($number);

		return $number.$k;
	}

	public static function space_digit($number)
	{
		if ($number > 999) {
			$rev_number = strrev($number);
			$number = '';
			for ($i = 0; $i < strlen($rev_number); $i++) {
				if ($i%3 == 0) {
					$number = '&nbsp;'.$number;
				}
				$number = $rev_number[$i].$number;
			}
		}
		return $number;
	}

	public static function get_horo($date) {
		if ( ! ($date instanceof DateTime)) {
			$date = preg_replace('/[^-\d\.\/]/', '', $date);
			$date = new DateTime($date);
		}
		$date = $date->format('md');

		$key = 'aquarius'; // Водолей
		if ($date >= 1222 OR $date <= 120) { $key = 'capricornus'; // Козерог
		} elseif ($date >= 1123) { $key = 'sagittarius'; // Стрелец
		} elseif ($date >= 1024) { $key = 'scorpio'; // Скорпион
		} elseif ($date >= 924) {  $key = 'libra'; // Весы
		} elseif ($date >= 824) {  $key = 'virgo'; // Дева
		} elseif ($date >= 724) {  $key = 'leo'; // Лев
		} elseif ($date >= 622) {  $key = 'cancer'; // Рак
		} elseif ($date >= 522) {  $key = 'gemini'; // Близнецы
		} elseif ($date >= 421) {  $key = 'taurus'; // Телец
		} elseif ($date >= 321) {  $key = 'aries'; // Овен
		} elseif ($date >= 220) {  $key = 'pisces'; // Рыбы
		}

		return $key;
	}

}
