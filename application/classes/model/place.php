<?php

/**
 * Модель мест размещения банеров
 *
 * @author nergal
 * @package btlady
 */
class Model_Place extends ORM
{
	/**
	 * Имя таблицы
	 * @var string
	 */
    protected $_table_name = 'places';

    protected $_has_many = array(
    	'banners' => array(
    		'model' => 'banner',
		'foreign_key' => 'place_id',
    		'through' => 'banner_places',
    	),
    );
}
