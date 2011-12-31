<?php

/**
 * Модель категории
 *
 * @author nergal
 * @package btlady
 */
class Model_Speciality extends ORM implements Model_Abstract_Interface_Section
{
	/**
	 * Имя таблицы
	 * @var string
	 */
    protected $_table_name = 'specialities';

    /**
	 * Внешние связи "один ко многим"
	 * @var array
	 */
    protected $_has_many = array(
		'questions'   => array(
			'model' => 'question',
			'foreign_key' => 'section_id',
		),
		'consultants' => array(
			'model' => 'consultant',
			'foreign_key' => 'speciality_id',
		),
    );

    protected $_load_with = array('questions');

    /**
     * Выборка секции
     *
     * @param string $category
     * @return ORM
     */
    public function get_section($category)
    {
    	$query = $this->where('name_url', '=', $category);
    	return $query->find();
    }

    /**
     * Выборка дерева категорий
     *
     * @param integer $parent_id
     * @return ORM
     */
    public function get_tree($parent_id = 0)
    {
                $query = $this
                        ->from($this->_table_name)
                        ->where('parent_id', '=', $parent_id)
                        ->where('showhide', '=', 1)
                        ->order_by('order');

                return $query->find_all();
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
