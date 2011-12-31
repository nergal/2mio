<?php

/**
 * Модель банеров
 *
 * @author nergal
 * @package btlady
 */
class Model_Banner extends ORM
{
	/**
	 * Имя таблицы
	 * @var string
	 */
    protected $_table_name = 'banners';

    protected $_has_many = array(
    	'places' => array(
    		'model' => 'places',
		'foreign_key' => 'banner_id',
    		'through' => 'banner_places',
    	),
    );
}
