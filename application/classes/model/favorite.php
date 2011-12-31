<?php

/**
 * Модель закладок
 *
 * @author sokol
 * @author nergal
 * @package btlady
 */
class Model_Favorite extends ORM
{
	protected $_table_name = 'favorites';

    protected $_belongs_to = array(
    	'user' => array(
    		'model' => 'user',
    		'foreign_key' => 'user_id',
    	),
    	'article' => array(
			'model' => 'article',
			'foreign_key' => 'page_id',
    	)
    );

    protected $_load_with = array('article', 'article:type', 'article:section');
}
