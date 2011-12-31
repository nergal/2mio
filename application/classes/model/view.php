<?php

/**
 * Модель просмотров
 *
 * @author nergal
 * @package btlady
 */
class Model_View extends ORM
{
	/**
	 * Имя таблицы
	 * @var string
	 */
    protected $_table_name = 'page_visits';

    /**
     * Внешняя связь с материалом
     *
     * @var array
     */
    protected $_belongs_to = array(
    	'page' => array(
    		'model' => 'page',
			'foreign_key' => 'page_id',
    	),
    );

    /**
     * Кол-во просмотров для статьи
     *
     * @return integer
     */
    public function get_count()
    {
    	$counts = $this
    		->select(array('SUM("count")', 'counts'))
    		->group_by('page_id')
    		->find_all()
    		->get('counts');

    	return intVal($counts);
    }
}
