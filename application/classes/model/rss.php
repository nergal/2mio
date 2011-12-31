<?php

//  TODO : название rss неадекватно, это модель информеров

class Model_RSS extends ORM {
	
	protected $_table_name = 'rss_news';
	
	protected $_belongs_to = array(
		'rss_category' => array(
			'model' => 'rss_carogory',
			'foreign_key'  => 'rss_category_id',
		),
		'page' => array(
			'model' => 'page',
			'foreign_key'  => 'page_id',
		),
	);

	public function get_news_informers($rss_category_name)
	{
		$query = $this;
		
		if ( ! is_numeric($rss_category_name)) {
			$query
				->join('rss_categories', 'left')
				->on($this->_table_name.'.rss_category_id', '=', 'rss_categories.id')
				->where('rss_categories.name', '=', $rss_category_name);
		} else {
			$query
				->where($this->_table_name.'.rss_category_id', '=', $rss_category_name);
		}
			$query->order_by('order_id', 'ASC');
			
		return $query->find_all();
	}	

}
