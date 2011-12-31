<?php

/**
 * Модель кодов подтвеждения
 *
 * @author nergal
 * @package btlady
 */
class Model_Confirm extends ORM
{
	/**
	 * Имя таблицы
	 * @var string
	 */
    protected $_table_name = 'confirm_codes';
    
    protected $_primary_key = 'code';

    protected $_belongs_to = array(
    	'user' => array(
    		'model' => 'user',
		'foreign_key' => 'user_id',
    	),
    );
}
