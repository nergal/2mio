<?php

/**
 * Модель ответов
 *
 * @author     nergal
 * @package    btlady
 * @subpackage consultation
 */
class Model_Answer extends Model_Question {
	/**
	 * Алиас для выборки типа из type_pages
	 * @var string
	 */
	protected $_type_alias = 'answer';

	/**
	 * Имя таблицы
	 * @var string
	 */
	protected $_table_name = 'answers';

	/**
	 * Внешние связи "один ко многим"
	 * @var array
	 */
    protected $_belongs_to = array(
		'question' => array(
		    'model' => 'question',
		    'foreign_key' => 'question_id',
		),
		'consultant' => array(
			'model' => 'consultant',
			'foreign_key' => 'doctor_id',
		),
    );

    protected $_load_with = array('consultant', 'question');
}