<?php

class Model_Videogallery extends Model_Abstract_Section {

	/**
	 * Алиас для выборки типа из type_pages
	 * @var string
	 */
	protected $_type_alias = 'video';

	/**
	 * Внешние связи "один ко многим"
	 * @var array
	 */
    protected $_has_many = array(
		'section' => array(
		    'model' => 'videogallery',
		    'foreign_key' => 'parent_id',
		),
		'page'   => array(
			'model' => 'video',
			'foreign_key' => 'section_id',
		),
	);

}