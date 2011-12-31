<?php

/**
 * Модель тегов
 *
 * @author nergal
 * @package btlady
 */
class Model_Tag extends ORM
{
	/**
	 * Имя таблицы
	 * @var string
	 */
    protected $_table_name = 'tags';

    protected $_has_many = array(
    	'pages' => array(
    		'model' => 'article',
			'foreign_key' => 'tag_id',
    		'through' => 'pages_tags',
    	),
    );
    
    public function get_cloud($limit = 20) {
	$result = DB::select('t.name, COUNT("p.id") AS `cnt`')
	    ->from(array('tags', 't'))	
		->join(array('pages_tags', 'pt'))
		    ->on('pt.tag_id', '=', 't.id')
		->join(array('pages', 'p'))
		    ->on('pt.page_id', '=', 'p.id')
	    ->where('showhide', '=', 1)
	    ->group_by('t.id')
		->having('cnt', '>', 5)
	    ->order_by('cnt', 'DESC')
	    ->limit($limit)
	    ->execute()
	    ->as_array();
	
	return $result;
    }
}
