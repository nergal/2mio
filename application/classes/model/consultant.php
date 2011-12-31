<?php

/**
 * Модель консультантов
 *
 * @author nergal
 * @package btlady
 */
class Model_Consultant extends ORM
{
	/**
	 * Имя таблицы
	 * @var string
	 */
    protected $_table_name = 'consultants';

    /**
     * Связи для таблицы
     * @var array
     */
    protected $_belongs_to = array(
    	'user' => array(
    		'model' => 'user',
    		'foreign_key' => 'id',
    	),
    	'speciality' => array(
    		'model' => 'speciality',
    		'foreign_key' => 'speciality_id',
    	),
    );

    protected $_has_many = array(
    	'questions' => array(
    		'model' => 'question',
    		'foreign_key' => 'doctor_id',
    	),
    	'answers' => array(
    		'model' => 'answer',
    		'foreign_key' => 'doctor_id',
    	),
    );

    protected $_load_with = array('user', 'speciality');

    public function valid()
    {
    	return $this->user->has_role('consult');
    }
}
