<?php

/**
 * Модель вопросов
 *
 * @author     nergal
 * @package    btlady
 * @subpackage consultation
 */
class Model_Question extends ORM implements Model_Abstract_Interface_Page {
	/**
	 * Алиас для выборки типа из type_pages
	 * @var string
	 */
	protected $_type_alias = 'question';

	/**
	 * Имя таблицы
	 * @var string
	 */
	protected $_table_name = 'questions';

	/**
	 * Внешние связи "один ко многим"
	 * @var array
	 */
    protected $_belongs_to = array(
		'speciality' => array(
		    'model' => 'speciality',
		    'foreign_key' => 'section_id',
		),
		'user' => array(
			'model' => 'user',
			'foreign_key' => 'user_id',
		),
		'doctor' => array(
			'model' => 'consultant',
			'foreign_key' => 'doctor_id',
		),
    );

    /**
     * Внешние связи "один ко многим"
     * @var array
     */
    protected $_has_many = array(
    	'answers' => array(
    		'model' => 'answer',
    		'foreign_key' => 'question_id',
    	),
    );

    protected $_load_with = array('speciality');

	/**
	 * Выборка материала
	 *
	 * @param string $pagename
	 * @param boolean $show
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
     * @param boolean $show Выводить ли показываемые статьи
     * @return ORM
     */
    public function get_last($show = TRUE)
    {
        $query = $this->order_by($this->_table_name.'.date', 'desc');
        if ($show === TRUE) {
        	$query = $query->where($this->_table_name.'.showhide', '=', 1);
        }

        return $query->find();
    }

    /**
     * Выборка категории для построения пейджинга
     *
     * @param  string       $section  Имя категории
     * @param  integer      $page     Номер страницы
     * @param  integer      $limit    Статей на страницу
     * @param  boolean      $count    Сделать подсчет
     * @param  boolean|null $answered Выбирать с ответом (NULL - все)
     * @return integer|Database_Result
     */
	public function get_tree($section, $page = NULL, $limit = 10, $count = FALSE, $answered = NULL)
	{
		$section_table = 'specialities';
		$query = $this
			->join($section_table)
			->on($this->_table_name.'.section_id', '=', $section_table.'.id')
			->where($section_table.'.name_url', '=', $section)
			->where($section_table.'.showhide', '=', 1)
			->where($this->_table_name.'.showhide', '=', 1)
			->order_by($this->_table_name.'.date');

		if ($answered !== NULL) {
			$query->where($this->_table_name.'.answear_id', ($answered ? 'IS NOT' : 'IS'), NULL);
		}

		if ($count === FALSE) {
		    if ($page !== NULL) {
				$query->limit($limit)->offset($limit * (intVal($page) - 1));
		    }
		    return $query->find_all();
		} else {
		    return $query->count_all();
		}
    }

    /**
     * Подсчет кол-ва статей в выборке
     *
     * @param  string       $section  Имя категории
     * @param  boolean|null $answered Выбирать с ответом (NULL - все)
     * @return integer
     */
    public function get_count_tree($section, $answered = NULL)
    {
		return $this->get_tree($section, NULL, 10, TRUE, $answered);
    }

    /**
     * Валидация выбранной статьи
     *
     * @param string         $title    Заголовок из адреса
     * @param string|integer $category Категория из адреса
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

    public function allow_answer(Model_User $user)
    {
    	// TODO:
    	return TRUE;
    }
}