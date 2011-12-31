<?php

/**
 * Модель категории
 *
 * @author nergal
 * @package btlady
 */
abstract class Model_Abstract_Section extends ORM implements Model_Abstract_Interface_Section
{
	/**
	 * Алиас для выборки типа из type_pages
	 * @var string
	 */
	protected $_type_alias = NULL;

	/**
	 * Имя таблицы
	 * @var string
	 */
    protected $_table_name = 'sections';

    /**
	 * Внешние связи "один ко многим"
	 * @var array
	 */
    protected $_has_many = array(
		'section' => array(
		    'model' => 'section',
		    'foreign_key' => 'parent_id',
		),
		'page'   => array(
			'model' => 'page',
			'foreign_key' => 'section_id',
		),
	);

	protected $_belongs_to = array(
		'type' => array(
			'model' => 'sectiontype',
			'foreign_key' => 'type_id',
		),
		'parent' => array(
		    'model' => 'section',
		    'foreign_key' => 'parent_id',
		),
    );

    protected $_load_with = array('type', 'parent');

	/**
	 * Создание внутренних запросов модели
	 * с прикреплённым типом материала
	 *
	 * @param integer $type Тип запроса
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

    /**
     * Выборка секции
     *
     * @param string $category
     * @return ORM
     */
    public function get_section($category)
    {
    	$self = clone $this;
    	$query = $self->where($this->_table_name.'.name_url', '=', $category);
    	return $query->find();
    }

    /**
     * Выборка секции по id
     *
     * @param string $category
     * @return ORM
     */
    public function get_section_by_id($id)
    {
	$self = clone $this;
    	$query = $self->where($this->_table_name.'.id', '=', $id);
    	return $query->find();
    }

    /**
     * Выборка подлежащих категорий
     *
     * @param integer $id
     * @return array|NULL
     */
    public function get_childs($id)
    {
    	if ( ! $this->loaded()) {
    		$id = (is_numeric($id) ? $id : ($this->get_section($id)->id));
			$this->where($this->_table_name.'.parent_id', '=', $id);
			$data = $this->find_all();

			$list = array();
			foreach ($data as $item) {
				$list[] = $item->id;
			}

			$list[] = $id;
			return (array) $list;
    	}

    	return NULL;
    }

    /**
     * Выборка дерева категорий
     *
     * @param integer $parent_id
     * @return ORM
     */
    public function get_tree($parent_id = 0, $page = NULL, $limit = 10)
    {
                $query = $this
                        ->from($this->_table_name)
                        ->where($this->_table_name.'.showhide', '=', 1)
                        ->order_by($this->_table_name.'.order');
                if (is_numeric($parent_id)) {
                	$query->where($this->_table_name.'.parent_id', '=', $parent_id);
                } else {
                	$query
                		->join(array('sections', 'sub'))
                		->on($this->_table_name.'.id', '=', 'sub.parent_id')
                		->where('sub.name_url', '=', $parent_id);
                }

				$query->reset(FALSE);
				$count = $query->count_all();

				if ($page !== NULL) {
					$offset = $limit * (intVal($page) - 1);

					$query = $query
						->limit($limit)
						->offset($offset);
				}


				$items = $query->find_all();

                return array($items, $count);
    }

    /**
     * Описание структуры таблицы
     *
     * @return array
     */
    public function rules()
    {
        return array(
            'parent_id' => array(
                array('not_empty'),
                array('regex', array(':value', '/^[0-9]+$/')),
            ),
            'name' => array(
                array('not_empty'),
                array('min_length', array(':value', 2)),
                array('max_length', array(':value', 255)),
            ),
            'name_url' => array(
                array('not_empty'),
                array('regex', array(':value', '/^[-a-z0-9]+$/')),
            ),
            'description' => array(
                array('not_empty'),
                array('min_length', array(':value', 4)),
                array('max_length', array(':value', 3000)),
            ),
            'order' => array(
                array('not_empty'),
                array('regex', array(':value', '/^\d+$/')),
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
