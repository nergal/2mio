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
    
    public function get_last() {
        return $this->order_by('date');
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
     * Мета-линк для цепочки ORM для выборки видимых материалов
     *
     * @return ORM
     */
    public function visible()
    {
        return $this->where($this->_table_name.'.showhide', '=', 1);
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
