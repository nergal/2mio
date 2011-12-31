<?php

/**
 * Модель секции блогов
 *
 * @author tretyak
 * @package btlady
 */
class Model_Category extends Model_Abstract_Section {
	
	/**
	 * Алиас для выборки типа из type_pages
	 * @var string
	 */
	protected $_type_alias = 'category';
	
    /**
	 * Внешние связи "один ко многим"
	 * @var array
	 */
    protected $_has_many = array(
		'section' => array(
		    'model' => 'category',
		    'foreign_key' => 'parent_id',
		),
		'page'   => array(
			'model' => 'article',
			'foreign_key' => 'section_id',
		),
	);	

}
