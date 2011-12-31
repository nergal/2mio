<?php

class Model_Contestgallery extends Model_Gallery {

	/**
	 * Внешние связи "один ко многим"
	 * @var array
	 */
    protected $_has_many = array(
		'section' => array(
		    'model' => 'contestgallery',
		    'foreign_key' => 'parent_id',
		),
		'page'   => array(
			'model' => 'contestphoto',
			'foreign_key' => 'section_id',
		),
	);
}