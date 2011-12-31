<?php

/**
 * Модель областей
 *
 * @author nergal
 * @package btlady
 */
class Model_Region extends ORM
{
	protected $_table_name = 'regions';

    protected $_has_many = array(
    	'cities' => array(
    		'model' => 'city',
    		'foreign_key' => 'region_id',
    	),
    );

    protected $_belongs_to = array(
    	'country' => array(
    		'model' => 'country',
    		'foreign_key' => 'country_id',
    	),
    );

    protected $_load_width = array('country');
}
