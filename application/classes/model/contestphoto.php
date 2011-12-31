<?php

class Model_Contestphoto extends Model_Photo {
	/**
	 * Внешние связи "один ко многим"
	 * @var array
	 */
	protected $_belongs_to = array(
		'section' => array(
		    'model' => 'contestgallery',
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


	protected $_foreign_fields = array(
		'fee'          => 'int',
		'ip'           => 'int',
		'author'       => 'string',
		'email'        => 'string',
		'address'      => 'string',
		'phone'        => 'string',
		'schedule'     => 'string',
		'source'       => 'string',
		'announce'     => 'int',
		'exclusive'    => 'int',
		'isadvert'     => 'int',
		'iscontest'    => 'int',

		'age'               => 'int',
		'social_state'      => 'int',
		'subscribe_seldiko' => 'int',
	);
}