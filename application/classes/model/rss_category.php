<?php

class Model_RSS_Category extends ORM {
	
	protected $_table_name = 'rss_categories';
	
	protected $_has_many = array(
		'rss'   => array(
			'model' => 'rss',
			'foreign_key' => 'rss_category_id',
		),	
	);
}
