<?php defined('SYSPATH') or die('No direct script access.');
       
/**
 * Модель сообщений блогов
 *
 * @author tretyak
 * @package btlady
 */
Class Model_Topic extends Model_Abstract_Page {
	
	/**
	 * Алиас для выборки типа из type_pages
	 * @var string
	 */
	protected $_type_alias = 'topic';
	
	/**
	 * Внешние связи "один ко многим"
	 * @var array
	 */
	protected $_belongs_to = array(
		'section' => array(
		    'model' => 'blog',
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
    
    /**
     * Правила валидации
     * @var array
     */		
	public function rules()
	{
		return array(
			'title' => array(
				array('not_empty'),
				array('min_length', array(':value', 4)),
				array('max_length', array(':value', 255))
			),
			'body' => array(
				array('not_empty'),
				array('min_length', array(':value', 4)),
				array('max_length', array(':value', 3000)),
			)
		);
	}
}

?>

