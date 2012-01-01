<?php

/**
 * Модель старниц
 *
 * @author  nergal
 * @package btlady
 */
abstract class Model_Abstract_Page extends ORM implements Model_Abstract_Interface_Page {
	/**
	 * Алиас для выборки типа из type_pages
	 * @var string
	 */
	protected $_type_alias = NULL;

	/**
	 * Имя таблицы
	 * @var string
	 */
	protected $_table_name = 'pages';

	/**
	 * Внешние связи "один ко многим"
	 * @var array
	 */
	protected $_belongs_to = array(
		'section' => array(
		    'model' => 'section',
		    'foreign_key' => 'section_id',
		),
		'type' => array(
		    'model' => 'pagetype',
		    'foreign_key' => 'type_id',
		),
		'user' => array(
		    'model' => 'user',
		    'foreign_key' => 'user_id',
		),
	);

    /**
     * Внешние связи "один ко многим"
     * @var array
     */
    protected $_has_many = array(
    	'media' => array(
    		'model' => 'media',
    		'foreign_key' => 'page_id',
    	),
    	'comments' => array(
    		'model' => 'comment',
    		'foreign_key' => 'page_id',
    	),
    	'views' => array(
    		'model' => 'view',
    		'foreign_key' => 'page_id',
    	),
    	'tags' => array(
    		'model' => 'tag',
    		'foreign_key' => 'page_id',
    		'through' => 'pages_tags',
    	),
    );

    /**
     * Подгузка связей
     * @var array
     */
    protected $_load_with = array('section', 'type');


	protected $_foreign_fields = array(
		// Integer
		'audioversion' => 'int',
		'fee'          => 'int',
		'ip'           => 'int',

		// DateTime
		'date-event'   => 'datetime',

		// String
		'author'       => 'string',
		'email'        => 'string',
		'address'      => 'string',
		'phone'        => 'string',
		'schedule'     => 'string',
		'source'       => 'string',

		// Boolean
		'announce'     => 'int',
		'exclusive'    => 'int',
		'isadvert'     => 'int',
		'iscontest'    => 'int',
		'social_comments'    => 'int',
	);

	/**
	 * Создание внутренних запросов модели
	 * с прикреплённым типом материала
	 *
	 * @param  integer $type Тип запроса
	 * @return ORM
	 */
	protected function _build($type)
	{
		parent::_build($type);

		if ($type == Database::SELECT AND $this->_type_alias !== NULL) {
			$this->_db_builder->where('type.alias', '=', $this->_type_alias);
		}

		return $this;
	}

	public function __get($variable)
	{
		$data = parent::__get($variable);
	
		if ($variable == 'body') {
			$this->increment_views_count();
			
			// TODO: как-то убрать 
			$selector = '#(<div id=(?:"|\')block_similar(?:"|\')(?:[^>]*)>)(?P<text>[^<]*)(</div>)#ui';
			if (preg_match($selector, $data, $matches)) {
			    // TODO: сделать связку через ORM
			    $linked = ORM::factory('pagesimilar', array('page_id' => $this->id, 'single' => 1))->article;
			    
			    if ($linked->loaded()) {
				$link = View::factory()->link($linked);
				
				$var = explode(' - ', $matches['text']);
				$var = array_replace($var, array(1 => $link));
				$var = implode(' - ', $var);
				
				$data = preg_replace($selector, '<div id="block_similar">'.$var.'</div>', $data);
			    }
			}
		} elseif ($variable == 'views_count') {
			$cache = Cache::instance('memcache');
			$key = array('count_views', $this->id, date('Ymd'));
			$key = implode('_', $key);

			if ($counts = $cache->get($key)) {
				return intVal($counts) + 1;
			}
		}

		return $data;
	}

	public function __call($method, array $args)
	{
		if ($method != 'loaded' AND $this->loaded()) {
			$exploded = explode('_', strtolower($method));
			if (count($exploded) == 3 AND $exploded[2] == 'count') {
				$type = $action = NULL;
				if (in_array($exploded[0], array('increment', 'decrement'))) {
					$action = $exploded[0];
				}

				if (in_array($exploded[1], array('views', 'comments'))) {
					$type = $exploded[1];
				}

				if ($type !== NULL AND $action !== NULL) {
					$key = array('count', $type, $this->id, date('Ymd'));
					$key = implode('_', $key);

					$default = parent::__get($type.'_count');
					$result = Cache::instance('memcache')->$action($key, 1, $default);
					return $result;
				}
			}
		}

		return parent::__call($method, $args);
	}

	public function get_alias()
	{
		return $this->type->alias;
	}

	/**
	 * Выборка материала
	 *
	 * @param  string  $pagename
	 * @param  boolean $show
	 * @return ORM
	 */
    public function get_page($pagename, $show = FALSE)
    {
    	$query = $this->where($this->_table_name.'.name_url', '=', $pagename);

    	if ($show === TRUE) {
    		$query = $query->where($this->_table_name.'.showhide', '=', 1);
    	}

    	return $query->find();
    }


    /**
     * Выборка последнего материала
     *
     * @param  boolean $show Выводить ли показываемые статьи
     * @return ORM
     */
    public function get_last($show = TRUE)
    {
        $query = $this->order_by($this->_table_name.'.date', 'desc');
        if ($show === TRUE) {
        	$query = $query
        		    ->where($this->_table_name.'.showhide', '=', 1)
			    ->where($this->_table_name.'.date', '<', DB::expr('NOW()'));
        }

        return $query->find();
    }

    /**
     * Выборка последних материалов
     *
     * @param  boolean $show Выводить ли показываемые статьи
     * @param  integer $limit количество материаов в выборке
     * @return ORM
     */
    public function get_lasts($show = TRUE, $limit = 4, $section_id = NULL)
    {
		$query = $this
			->group_by($this->_table_name.'.id')
	    		->order_by($this->_table_name.'.date', 'desc');
        if ($show === TRUE) {
        	$query
				->where($this->_table_name.'.showhide', '=', 1)
				->where($this->_table_name.'.date', '<', DB::expr('NOW()'))
				->where($this->_table_name.'.date', '>', 0);
        }
        if ($section_id && intVal($section_id) > 0) {
			$query->where($this->_table_name.'.section_id', '=', $section_id);
		}
        $query->limit($limit);

        return $query->find_all();
    }
    
    /**
     * Выборка дерева последних материалов
     * 
     * @param  boolean $show Выводить ли показываемые статьи
     * @param  integer $limit количество материаов в выборке
     * @return ORM
     * 
     */
    
    public function get_tree_lasts($show = TRUE, $limit = 100, $section_id = NULL)
    {
		$query = $this
			->group_by($this->_table_name.'.id')
	        ->order_by($this->_table_name.'.date', 'desc');
        if ($show === TRUE) {
        	$query
				->where($this->_table_name.'.showhide', '=', 1)
				->where($this->_table_name.'.date', '<', DB::expr('NOW()'))
				->where($this->_table_name.'.date', '>', 0);
        }

        if ($section_id && intVal($section_id) > 0) {
			$childs = ORM::factory('section')->get_childs($section_id);
			$query->where($this->_table_name.'.section_id', 'IN', $childs);
		}
        $query->limit($limit);		
        
        return $query->find_all();
	}

    /**
     * Выборка последних материалов для RSS
     *
     * @param  integer $limit количество материаов в выборке
     * @return ORM
     */
    public function get_ya_rss($limit = 100)
    {
		$query = $this
			->where($this->_table_name.'.showhide', '=', 1)
			->where($this->_table_name.'.date', '<', DB::expr('NOW()'))
			->where($this->_table_name.'.yandex_rss', '=', 1)
			->group_by($this->_table_name.'.id')
	        ->order_by($this->_table_name.'.date', 'desc')
			->limit($limit);

        return $query->find_all();
    }
    
    public function get_partner_rss($limit = 100)
    {
		$query = $this
			->where($this->_table_name.'.date', '<', DB::expr('NOW()'))
			->where($this->_table_name.'.showhide', '=', 1)
			->where($this->_table_name.'.partners_rss', '=', 1)
			->group_by($this->_table_name.'.id')
	        ->order_by($this->_table_name.'.date', 'desc')
			->limit($limit);

        return $query->find_all();	
	}

    public function get_fixed($limit = 3, $with_images = FALSE)
    {
    	$query = $this
		->where($this->_table_name.'.date', '<', DB::expr('NOW()'))
    		->order_by($this->_table_name.'.fix', 'desc')
    		->order_by($this->_table_name.'.date', 'desc')
    		->limit($limit);

    	if ($with_images === TRUE) {
    		$query->where($this->_table_name.'.photo', 'IS NOT', NULL);
    	}

    	return $query->find_all();
    }
    
    public function get_recommended($limit = 5, $with_images = FALSE)
    {
    	$query = $this
			->where($this->_table_name.'.showhide', '=', 1)
			->where($this->_table_name.'.date', '<', DB::expr('NOW()'))
			->where($this->_table_name.'.recommended', '=', 1);

    	if ($with_images === TRUE) {
    		$query->where($this->_table_name.'.photo', 'IS NOT', NULL);
    	}
		
		$query
			->order_by($this->_table_name.'.date', 'desc')
			->limit($limit);

    	return $query->find_all();
    }    
    
     /**
     * Подсет количества материалов для всего дерева начинающихся с каждой букывы алфавита 
     *
     * @param  string  $section Имя категории
     * @return array
     */
    public function get_active_literas($section)
    {
		$abc_counter = array();
		
		foreach (Kohana::$config->load('abc.ru') as $key => $litera) {
			
			$query = $this
				->where($this->_table_name.'.showhide', '=', 1)
				->where($this->_table_name.'.date', '<', DB::expr('NOW()'))
				->where($this->_table_name.'.title', 'RLIKE', DB::expr('"^[0-9\" \.]*'.$litera.'"'));
				
			$childs = ORM::factory('section')->get_childs($section);
			$query->where($this->_table_name.'.section_id', 'IN', $childs);			
			
			$litera_count = $query->count_all();
			
			$abc_counter[$key]	= $litera_count;
		}
		
		return $abc_counter;
	}
    
     /**
     * Выборка категории для построения пейджинга по литере
     *
     * @param  string  $section Имя категории
     * @param  integer $page    Номер страницы
     * @param  integer $limit   Статей на страницу
     * @param  boolean $count   Сделать подсчет
     * @param  integer $litera  Порядковый номер буквицы в алфавите
     * @return integer|Database_Result
     */
    public function get_tree_by_litera($section, $page = NULL, $limit = 10, $count = FALSE, $litera_num = -1)
    {
		$abc_array = Kohana::$config->load('abc.ru');
		
		$query = $this->reset();		
		
		$query = $this
			    ->where($this->_table_name.'.showhide', '=', 1)
			    ->where($this->_table_name.'.date', '<', DB::expr('NOW()'));
		
		if ($litera_num >= 0)
		{
			$litera = $abc_array[$litera_num];
			$query = $this->where($this->_table_name.'.title', 'RLIKE', DB::expr('"^[0-9\" \.]*'.$litera.'"'));			
		}

		$childs = ORM::factory('section')->get_childs($section);
		$query->where($this->_table_name.'.section_id', 'IN', $childs);
		
		if ($count === FALSE) {
		    
		    if ($page !== NULL) {
		    	$offset = $limit * (intVal($page) - 1);

				$query->limit($limit)
					->offset($offset);
		    }
		    
		    $query->order_by($this->_table_name.'.title');
		    
		    $data = $query->find_all();
		    
		    return $data;
		} else {
			return $query->count_all();
		}
			
	}

    /**
     * Выборка категории для построения пейджинга
     *
     * @param  string  $section Имя категории
     * @param  integer $page    Номер страницы
     * @param  integer $limit   Статей на страницу
     * @param  boolean $count   Сделать подсчет
     * @return integer|Database_Result
     */
	public function get_tree($section, $page = NULL, $limit = 10, $count = FALSE, $order = NULL, $period = NULL)
	{
		$section_table = 'sections';
		$this->reset(TRUE);
		
		$query = $this
			// ->join($section_table)
			// ->on($this->_table_name.'.section_id', '=', $section_table.'.id')
			// ->where($section_table.'.showhide', '=', 1)
			->where($this->_table_name.'.date', '<', DB::expr('NOW()'))
			->where($this->_table_name.'.showhide', '=', 1);
			
		if ($period == 'week') {
			$query->where($this->_table_name.'.date', '>', DB::expr('DATE_SUB(SYSDATE(), INTERVAL 7 DAY) '));
		} elseif ($period == 'month') {
			$query->where($this->_table_name.'.date', '>', DB::expr('DATE_SUB(SYSDATE(), INTERVAL 31 DAY) '));
		}

		if ($section == 'all') {
			$query
				->where($this->_table_name.'.section_id', '>', 300) // исключить старые разделы
				->where($this->_table_name.'.section_id', 'NOT IN', array(477, 420, 436)) // исключить разделы
				->where($this->_table_name.'.type_id', '=', 2); // только статьи
		} else {
			$childs = ORM::factory('section')->get_childs($section);
			$query->where($this->_table_name.'.section_id', 'IN', $childs);
		}
		
		/*
		$query
			->select(array('COUNT("comments.id")', 'comments_count'))
			->join('comments', 'left')
			->on('comments.page_id', '=', $this->_table_name.'.id')

			->select(array('SUM("page_visits.count")', 'views_count'))
			->join('page_visits', 'left')
			->on('page_visits.page_id', '=', $this->_table_name.'.id')
			->group_by($this->_table_name.'.id');
		*/

		if ($count === FALSE) {
			switch ($order) {
				case 'views':
					$query->order_by('views_count', 'DESC');
				break;
				case 'comments':
					$query->order_by('comments_count', 'DESC');
				break;
				case 'title':
					$query->order_by('title');
				break;
				default:
					$query->order_by('date', 'DESC');
			}

		    if ($page !== NULL) {
		    	$offset = $limit * (intVal($page) - 1);

				$query->limit($limit)
					->offset($offset);
		    }
		    $data = $query->find_all();
		    return $data;
		} else {
		    return $query->count_all();
		}
    }

    /**
     * Подсчет кол-ва статей в выборке
     *
     * @param  string $section Имя категории
     * @return integer
     */
    public function get_count_tree($section, $period)
    {
		return $this->get_tree($section, NULL, 10, TRUE, NULL, $period);
    }
    
    /**
     * Выборка рекоммендованных статей для построения пейджинга
     *
     * @param  integer $page    Номер страницы
     * @param  integer $limit   Статей на страницу
     * @param  boolean $count   Сделать подсчет
     * @param  string  $order   Порядок сортировки материалов
     * @param  string  $period  Период выборки материалов
     * @return integer|Database_Result
     */
     
    public function get_tree_recommend($page = NULL, $limit = 10, $count = FALSE, $order = NULL, $period = NULL)
	{
		$this->reset(TRUE);
		
		$query = $this
			->where($this->_table_name.'.showhide', '=', 1)
			->where($this->_table_name.'.date', '<', DB::expr('NOW()'))
			->where($this->_table_name.'.recommended', '=', 1);
			
		if ($period == 'week') {
			$query->where($this->_table_name.'.date', ">", DB::expr('DATE_SUB(SYSDATE(), INTERVAL 7 DAY) '));
		} elseif ($period == 'month') {
			$query->where($this->_table_name.'.date', ">", DB::expr('DATE_SUB(SYSDATE(), INTERVAL 31 DAY) '));
		}
		
		if ($count === FALSE) {
			switch ($order) {
				case 'views':
					$query->order_by('views_count', 'DESC');
				break;
				case 'comments':
					$query->order_by('comments_count', 'DESC');
				break;
				case 'title':
					$query->order_by('title');
				break;
				default:
					$query->order_by('date', 'DESC');
			}

		    if ($page !== NULL) {
		    	$offset = $limit * (intVal($page) - 1);

				$query->limit($limit)
					->offset($offset);
		    }
		    $data = $query->find_all();
		    return $data;
		} else {
		    return $query->count_all();
		}		

	}
	
    /**
     * Подсчет кол-ва статей в выборке
     *
     * @param  string $period Период выборки материалов
     * @return integer
     */
	public function get_count_tree_recommend($period)
	{
		return $this->get_tree_recommend(NULL, 10, TRUE, NULL, $period);
	}

    /**
     * Валидация выбранной статьи
     *
     * @param  string         $title    Заголовок из адреса
     * @param  string|integer $category Категория из адреса
     * @return boolean|ORM
     */
    public function valid($title = NULL, $category = NULL)
    {
    	if ($this->loaded()) {
    		if ($title !== NULL) {
				if (View::factory()->transliterate($this->title) != $title) {
					return FALSE;
				}
    		}

    		if ($category !== NULL) {
    			// TODO: сделать валидацию категории
    		}

    		return $this;
    	}

    	return FALSE;
    }

    /**
     * Выборка материалов принадлежащих секции (включая подсекции)
     *
     * @param  integer $section_id
     * @param  integer $limit
     * @return array
     */
    public function get_by_section_id($section_id = 304, $limit = 2, $announcing = false) // Здоровье
    {
		$section_id = (array) $section_id;

		$query = $this
			->where('pages.showhide', "=", 1)
			->where('pages.date', '<', DB::expr('NOW()'))
			->and_where('section.id', ">", 300) // отсекаем старые разделы
			->and_where_open()
				->where('section.id', "IN", $section_id)
				->or_where('section.parent_id', "IN", $section_id)
			->and_where_close()
			->order_by('date', 'DESC')
			->limit($limit);

		if ($announcing)
		{
			$query->where('pages.announcing', "=", 1);
		}

		return $query->find_all();
    }

    /**
     * Выборка для главного блока (TV)
     *
     * TODO: при наличии нескольких подобных метов -- слить в один
     *
     * @param  integer $offset
     * @param  integer $limit
     * @return array
     */
    public function get_last_for_tv_block($offset = 0, $limit = 5)
    {
		$query = $this
			->where('fix', "=", 1)
			->where('section_id', '>', 300)
			->where('date', '<', DB::expr('NOW()'))
			->order_by('date', 'DESC')
			->limit($limit)
			->offset($offset*$limit);

		return $query->find_all();
    }

    /**
     * Выборка top-view материалов
     *
     * @param  integer $type (берем из type_pages)
     * @param  integer $limit
     * @return array
     */
	public function get_top_view($limit = 6)
	{
		$query = $this
			->where($this->_table_name.'.showhide', "=", 1)
			->where($this->_table_name.'.date', '<', DB::expr('NOW()'))
			->where('section.id', ">", 300) // отсекаем старые разделы
			->where('date', ">", DB::expr('DATE_SUB(SYSDATE(), INTERVAL 7 DAY) ')) // за последнюю неделю
			->order_by('views_count', 'DESC')
			->limit($limit);

		if ($limit > 1) {
			return $query->find_all();
		} else {
			return $query->find();
		}
	}

    /**
     * Выборка top-commented материалов
     *
     * @param  integer $type (берем из type_pages)
     * @param  integer $limit
     * @return array
     */
	public function get_top_comment($limit = 6)
	{
		$query = $this
			->where($this->_table_name.'.showhide', "=", 1)
			->where($this->_table_name.'.date', '<', DB::expr('NOW()'))
			->where('section.id', ">", 300) // отсекаем старые разделы
			->where('comments_count', ">" , 0)
			->where('date', ">", DB::expr('DATE_SUB(SYSDATE(), INTERVAL 7 DAY)')) // за последнюю неделю
			->order_by('comments_count', 'DESC')
			->limit($limit);

		if ($limit > 1) {
			return $query->find_all();
		} else {
			return $query->find();
		}
	}

	/**
	 * Мета-линк для цепочки ORM для выборки видимых материалов
	 *
	 * @return ORM
	 */
	public function visible()
	{
		return $this->where($this->_table_name.'.showhide', '=', 1);
	}

    /**
     * Выборка только видимых материалов
     *
     * @return array
     */
    public function get_visible()
    {
		$query = $this->visible();
		return $query->find();
    }

    /**
     * Similar pages selection
     *
     * @param  integer $limit
     * @return Database_Result
     */
    public function get_similar($limit = 5) {
        if ($this->loaded()) {
            $sql = '
                SELECT
                    `x`.`id`,
                    `x`.`title`,
                    `s`.`name_url`,
                    `tp`.`alias`
                FROM
                (
                    (
                        SELECT
                            `p`.`id`,
                            `p`.`title`,
                            `p`.`type_id`,
                            `p`.`section_id`,
                            0
                        FROM `pages_similar` `ps`
                                JOIN `pages` `p` ON `p`.`id` = `ps`.`similar_id`
                        WHERE `ps`.`page_id` = :page_id
                    ) UNION (
                        SELECT
                            `p`.`id`,
                            `p`.`title`,
                            `p`.`type_id`,
                            `p`.`section_id`,
                            COUNT(`p`.`id`)
                        FROM `pages` `p`
                            LEFT JOIN `pages_tags` `pt` ON `p`.`id` = `pt`.`page_id`
                        WHERE
                            `p`.`showhide` = 1
                            AND `pt`.`tag_id` = SOME(SELECT `tag_id` FROM `pages_tags` WHERE `page_id` = :page_id)
                            AND `p`.`id` != :page_id
                        GROUP BY `p`.`id`
                        ORDER BY COUNT(`p`.`id`) DESC
                        LIMIT 5
                    )
                ) `x`
                    JOIN `type_pages` `tp` ON `tp`.`id` = `x`.`type_id`
                    JOIN `sections` `s` ON `s`.`id` = `x`.`section_id`
                LIMIT '.intVal($limit);
            
            $query = DB::query(Database::SELECT, $sql);
            $query->param(':page_id', $this->id);
            
            return $query->execute()->as_array();
            /*
            $result = ORM::factory('page')
                ->join(array('pages_tags', 'pt'), 'LEFT')
                ->on('pt.page_id', '=', $this->_table_name.'.id')
                ->visible()
                ->where('pt.tag_id', '=', DB::expr('SOME(SELECT tag_id FROM pages_tags WHERE page_id = '.$this->id.')'))
                ->where($this->_table_name.'.id', '!=', $this->id)
                ->group_by($this->_table_name.'.id')
                ->order_by('COUNT("'.$this->_table_name.'.id")', 'DESC')
                ->limit($limit);

            return $result->find_all();
            */
        }

        throw new Kohana_Exception('Similar method only for loaded objects');
    }


    /**
     * Описание структуры таблицы
     *
     * @return array
     */
	public function rules()
	{
        return array(
            'name_url' => array(
                array('min_length', array(':value', 4)),
                array('max_length', array(':value', 3000)),
            ),
            'title' => array(
                array('not_empty'),
                array('min_length', array(':value', 4)),
                array('max_length', array(':value', 3000)),
            ),
            'body' => array(
                // array('not_empty'),
                array('min_length', array(':value', 4)),
                array('max_length', array(':value', 80000)),
            ),
            'description' => array(
                // array('not_empty'),
                array('min_length', array(':value', 4)),
                array('max_length', array(':value', 3000)),
            ),
            'date' => array(
                array('not_empty'),
                array('regex', array(':value', '/^([0-9]{4})-([0-9]{2})-([0-9]{2}) ([0-9]{2}):([0-9]{2}):([0-9]{2})$/')),
            ),
            'fix' => array(
                array('not_empty'),
                array('min_length', array(':value', 1)),
                array('max_length', array(':value', 1)),
                array('regex', array(':value', '/^(1|0)?$/')),
            ),
            'showhide' => array(
                array('not_empty'),
                array('min_length', array(':value', 1)),
                array('max_length', array(':value', 1)),
                array('regex', array(':value', '/^(1|0)?$/')),
            ),
        );
    }
}
