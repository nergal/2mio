<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Перегрузка view
 *
 * @author nergal
 */
class View extends Plater {

	/**
	 * Правила для транслитерации
	 * @var array
	 */
    protected $_translate_rules = array(
        "Ґ"=>"G",  "Ё"=>"YO",  "Є"=>"YE", "Ї"=>"YI",  "І"=>"I",
        "і"=>"i",  "ґ"=>"g",   "ё"=>"yo", "№"=>"",    "є"=>"je",
        "ї"=>"yi", "А"=>"A",   "Б"=>"B",  "В"=>"V",   "Г"=>"G",
        "Д"=>"D",  "Е"=>"E",   "Ж"=>"ZH", "З"=>"Z",   "И"=>"I",
        "Й"=>"Y",  "К"=>"K",   "Л"=>"L",  "М"=>"M",   "Н"=>"N",
        "О"=>"O",  "П"=>"P",   "Р"=>"R",  "С"=>"S",   "Т"=>"T",
        "У"=>"U",  "Ф"=>"F",   "Х"=>"H",  "Ц"=>"TS",  "Ч"=>"CH",
        "Ш"=>"SH", "Щ"=>"SCH", "Ъ"=>"",   "Ы"=>"YI",  "Ь"=>"",
        "Э"=>"E",  "Ю"=>"YU",  "Я"=>"YA", "а"=>"a",   "б"=>"b",
        "в"=>"v",  "г"=>"g",   "д"=>"d",  "е"=>"e",   "ж"=>"zh",
        "з"=>"z",  "и"=>"i",   "й"=>"y",  "к"=>"k",   "л"=>"l",
        "м"=>"m",  "н"=>"n",   "о"=>"o",  "п"=>"p",   "р"=>"r",
        "с"=>"s",  "т"=>"t",   "у"=>"u",  "ф"=>"f",   "х"=>"h",
        "ц"=>"ts", "ч"=>"ch",  "ш"=>"sh", "щ"=>"sch", "ъ"=>"",
        "ы"=>"yi", "ь"=>"",    "э"=>"e",  "ю"=>"yu",  "я"=>"ya",
        " "=>"_",  ","=>"",    "."=>"",   ":"=>"",    "("=>"",
        ")"=>"",
    );

    /**
     * Генерация ссылки
     *
     * @param ORM $object
     * @param string $title
     * @param array $attributes
     * @return string
     */
    public function link($object, $title = NULL, $attributes = NULL)
    {
		if ($object instanceof Model_Menu) {
			if (empty($title)) {
				$title = $object->title;
			}

			if ($object->section->loaded()) {
				return $this->link($object->section, $title, $attributes);
			} elseif ($object->page->loaded()) {
				return $this->link($object->page, $title, $attributes);
			}
		}

		if (empty($title)) {
			if (isset($object->title)) {
				$title = $object->title;
		    } elseif (isset($object->name)) {
			    $title = $object->name;
		    } elseif (isset($object->username)) {
		    	$title = $object->get_user_title();
		    }

		    $title = Helper::escape($title);
		}
		return HTML::anchor($this->uri($object), $title, $attributes);
    }
    
    /**
     * Генерация ссылки для хлебных крошек
     *
     * @param ORM $object
     * @param string $title
     * @param array $attributes
     * @return string
     */
    public function link_bread($object, $title = NULL)
    {
		if (empty($title)) {
			if (isset($object->title)) {
				$title = $object->title;
		    } elseif (isset($object->name)) {
			    $title = $object->name;
		    } elseif (isset($object->username)) {
		    	$title = $object->get_user_title();
		    }
			$title = Helper::escape($title);
		}
		return '<span typeof="v:Breadcrumb"><a property="v:title" rel="v:url" href="'.rtrim(URL::base(TRUE), '/').$this->uri($object).'">'.$title.'</a></span>';
    }
     

    /**
     * Генерация урла
     *
     * @param ORM $object
     * @param array $params
     * @return string
     */
    public function uri($object = NULL, Array $params = NULL)
    {
    	if ($object instanceof Model_User) {
    		$uri = array();

	    	$route = array(
	    		'name' => 'profile',
	    		'params' => array(
	    			'id' => $object->id,
	    		),
	    	);
	    } elseif ($object instanceof Model_Menu) {
			if ($object->section->loaded()) {
				return $this->uri($object->section);
			} elseif ($object->page->loaded()) {
				return $this->uri($object->page);
			}
    	} elseif ($object instanceof Model_Question) {
		    $uri = array();

	    	$route = array(
	    		'name' => 'consult-view',
	    		'params' => array(
	    			'category' => $object->speciality->name_url,
	    			'id' => $object->id,
	    			'title' => $object->name_url,
	    		),
	    	);
		} elseif ($object instanceof Model_Speciality) {
		    $uri = array();

		    $route = array(
		    	'name' => 'consult-list',
		    	'params' => array('category' => $object->name_url),
		    );
		} elseif ($object instanceof Model_Gallery) {
	    	$route = array(
	    		'name' => (isset($params['add']) AND $params['add'] === TRUE) ? 'photo-add' : 'gallery-list',
	    		'params' => array(
	    			'category' => $object->name_url,
	    			'id' => $object->id,
	    		),
	    	);
		} elseif ($object instanceof Model_Photo) {
	    	$route = array(
	    		'name' => 'gallery-view',
	    		'params' => array(
	    			'category' => $object->section->name_url,
	    			'id' => $object->id,
	    		),
	    	);
		} elseif ($object instanceof Model_Videogallery) {
	    	$route = array(
	    		'name' => 'video',
	    		'params' => array(
	    			'category' => $object->name_url,
	    			'id' => $object->id,
	    		),
	    	);
		} elseif ($object instanceof Model_Video) {
	    	$route = array(
	    		'name' => 'video-view',
	    		'params' => array(
	    			'category' => $object->section->name_url,
	    			'id' => $object->id,
	    		),
	    	);
		} elseif ($object instanceof Model_Tag) {
			$route = array(
	    		'name' => 'tags-view',
	    		'params' => array(
	    			'tag' => $object->name,
	    		),
	    	);
		} elseif ($object instanceof Model_Abstract_Page) {
		    $uri = array();
			$rout_ext = (isset($params) AND ! empty($params['operation'])) ? '-operation' : '-view';
			$alias = $object->type->alias;

			if ($alias == 'photo') {
				$alias = 'gallery';
			}

			if ($object->type->loaded()) {
			    $route = array(
					'name' => $object->type->alias.$rout_ext,
		    		'params' => array(
		    			'category' => $object->section->name_url,
		    			'id' => $object->id,
		    			'title' => $this->transliterate($object->title),
		    			'operation' => (isset($params) AND ! empty($params['operation'])) ? $params['operation'] : 'view',
		    		),
		    	);
			} else {
				Kohana::$log->add(Log::ERROR, 'Невозможно узнать тип статьи page_id='.$object->id);
			}
		} elseif ($object instanceof Model_Abstract_Section) {
		    $uri = array();
		    $rout_ext = (isset($params) AND ! empty($params['operation'])) ? '-operation' : '';

		    if ($object->type->loaded()) {
			    $route = array(
					'name' => $object->type->alias.$rout_ext,
			    	'params' => array(
						'category' => $object->name_url,
						'operation' => (isset($params) AND ! empty($params['operation'])) ? $params['operation'] : 'view'
			    	),
			    );
			} else {
				Kohana::$log->add(Log::ERROR, 'Невозможно узнать тип секции page_id='.$object->id);
		    }
		}

		$uri = isset($route) ? URL::site(Route::get($route['name'])->uri($route['params'])) : '/';
		return $uri;
    }

    /**
     * Транслитерация строк
     *
     * @param string $string
     * @param boolean $backward
     * @return string
     */
    public function transliterate($string, $backward = FALSE)
    {
        if ($backward) {
            $transliterate = array_reverse(array_flip($this->_translate_rules));
            unset($transliterate[""]);
            $string = strtr(mb_strtolower($string), $transliterate);
        } else {
            $string = trim($string);
            $string = strtr($string, $this->_translate_rules);
            $string = preg_replace('/[^-()_a-z0-9]/ui', '', $string);
            $string = strtolower($string);
        }

        $string = str_replace('_', '-', $string);
        $string = preg_replace('/\-{2,}/ui', '-', $string);

        return $string;
    }

    public function render_tags(Model_Abstract_Page $page)
    {
    	$tags = $page->tags->find_all();

    	$links = array();
    	foreach ($tags as $tag) {
    		$links[] = $this->link($tag);
    	}

    	return implode(', ', $links);
    }

    /**
     * Выборка блоков
     *
     * @param array $name
     * @param string $context
     * @return string
     */
    public function get_blocks($name, $context)
    {
        $request = Request::factory('/blocks/render/');
        return $request->query('names', $name)
                        ->query('context', $context)
                        ->execute()
                        ->body();
    }

    public function photo(ORM $object, $size = NULL, $type = 'cropr')
    {
		if ($object->loaded()) {
			if ($object instanceof Model_Photo OR ($object instanceof Model_Abstract_Page AND $object->get_alias() == 'photo')) {
				$url = array('/'.(($size === NULL) ? 'uploads' : 'thumbnails'));

				if (preg_match('#^([a-f0-9]{32,})\.(gif|jpe?g|png)$#ui', $object->photo)) {
					$url[]= substr($object->photo, 0, 2);
					$url[]= substr($object->photo, 2, 4);

					$filename = $object->photo;
				} else {
					$url = array('/'.(($size === NULL) ? 'images' : 'thumbnails').'/articles');
					$photo = explode('/', $object->photo);
					$photo = array_filter($photo);

					$filename = array_splice($photo, -1, 1);
					$filename = end($filename);
					$url = array_merge($url, $photo);
				}

				if ($size !== NULL) {
					$url[]= "{$type}_{$size}";
				}

				$url[]= $filename;

				$url = array_filter($url);
				return implode('/', $url);
			} elseif ($object instanceof Model_Video OR ($object instanceof Model_Abstract_Page AND $object->get_alias() == 'video')) {
				$url = '/thumbnails/';
				$url.= substr($object->photo, 0, 2).'/';
				$url.= substr($object->photo, 2, 4).'/';

				if ($size !== NULL) {
					$url.= "{$type}_{$size}/";
				}

				$url.= str_replace('.flv', '.png', $object->photo);

				return $url;
			} elseif ($object instanceof Model_Media) {
				$url = array('/'.(($size === NULL) ? 'images' : 'thumbnails').'/articles');
				$photo = explode('/', basename($object->name));
				$photo = array_filter($photo);

				$filename = array_splice($photo, -1, 1);
				$filename = end($filename);
				$url = array_merge($url, $photo);
				if ($size !== NULL) {
					$url[]= "{$type}_{$size}";
				}

				$url[]= $filename;

				$url = array_filter($url);
				return implode('/', $url);
			} elseif ($object->photo) {
				$url = array('/'.(($size === NULL) ? 'images' : 'thumbnails').'/articles');

				$photo = explode('/', $object->photo);
				$photo = array_filter($photo);

				$filename = array_splice($photo, -1, 1);
				$url = array_merge($url, $photo);

				if ($size !== NULL) {
					$url[] = $type.'_'.$size;
				}
				$url[] = end($filename);
				return implode('/', $url);
			}
		}

		return '/i/placeholder.gif';
    }

	public function video(ORM $object)
	{
		if ($object->loaded()) {
			if ($object instanceof Model_Video) {
				$url = '/uploads/';
				$url.= substr($object->photo, 0, 2).'/';
				$url.= substr($object->photo, 2, 4).'/';

				$url.= $object->photo;

				return URL::site($url, 'http');
			}
		}

		return NULL;
    }

    public function banner($place)
    {
    	$place = ORM::factory('place')
    		->where('name', '=', $place)
    		->find();
    	$banners = $place->banners
    		->where('MD5("code")', '=', DB::expr('`hash`'))
    		->where('showhide', '=', 1)
    		->find_all();

    	$html = array();
    	foreach ($banners as $banner) {
    		$html[] = $banner->code;
    	}

		return implode('<br />', $html);
    }

    public function plural($count, $form1, $form2, $form3)
    {
	return Helper::plural($count, $form1, $form2, $form3);
    }
}
