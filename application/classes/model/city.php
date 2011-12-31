<?php

/**
 * Модель городов
 *
 * @author nergal
 * @package btlady
 */
class Model_City extends ORM
{
	protected $_table_name = 'cities';

    protected $_belongs_to = array(
    	'region' => array(
    		'model' => 'region',
    		'foreign_key' => 'region_id',
    	),
    );

    protected $_load_width = array('region', 'region:country');

    public function find_similar($string)
    {
    	$query = $this
    		->where(DB::Expr('INSTR(LOWER('.$this->_db->quote($string).'), LOWER(name))'), '>', 0)
            ->or_where(DB::Expr('INSTR(LOWER('.$this->_db->quote($string).'), LOWER(name_url))'), '>', 0)
    		->find();

    	return $query;
    }
}
