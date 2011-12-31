<?php

/**
 * Модель секции блогов
 *
 * @author tretyak
 * @package btlady
 */
class Model_Blog extends Model_Abstract_Section {
	
	/**
	 * Алиас для выборки типа из type_pages
	 * @var string
	 */
	protected $_type_alias = 'blog';
	
    /**
	 * Внешние связи "один ко многим"
	 * @var array
	 */
    protected $_has_many = array(
		'section' => array(
		    'model' => 'blog',
		    'foreign_key' => 'parent_id',
		),
		'page'   => array(
			'model' => 'topic',
			'foreign_key' => 'section_id',
		),
	);

}
