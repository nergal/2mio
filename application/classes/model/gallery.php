<?php

class Model_Gallery extends Model_Abstract_Section {

	/**
	 * Алиас для выборки типа из type_pages
	 * @var string
	 */
	protected $_type_alias = 'galleries';

	/**
	 * Внешние связи "один ко многим"
	 * @var array
	 */
    protected $_has_many = array(
		'section' => array(
		    'model' => 'gallery',
		    'foreign_key' => 'parent_id',
		),
		'page'   => array(
			'model' => 'photo',
			'foreign_key' => 'section_id',
		),
	);
	
    /**
     * Описание структуры таблицы
     *
     * @return array
     */
	public function rules()
	{
		$rules = parent::rules();
		
		if (isset($rules['description'])) {
			unset($rules['description']);
		}
		return $rules;
	}
	
}
