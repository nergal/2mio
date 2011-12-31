<?php
/**
 * Дополнительные опции для данных БД
 *
 * @author sokol, 2011
 */
 
class Options 
{
	private $model;
	private $datatype_to_table = array(
		'int' => 'pages_int_options',
		'float' => 'pages_float_options',
		'datetime' => 'pages_int_options',
		'string' => 'pages_string_options',
		'text' => 'pages_text_options',
		'boolean' => 'pages_int_options',
		'blob' => 'pages_text_options'
	);
	
	public function __construct()
	{
		$this->model = Model::factory('options');
		$this->model->init($this->datatype_to_table);
	}
	
	public function get_options($table_name, $page_id)
	{
		$page_id = intval($page_id);
		
		if($table_name && $page_id)
		{
			$this->model->get_options($table_name, $page_id);
		}
	}
	
	public function get_type_values()
	{
		return $this->model->get_type_values();
	}
}
