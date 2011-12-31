<?php

class Model_Menu extends ORM {
	protected $_table_name = 'menu_main';
	private $_cached = NULL;

	protected $_belongs_to = array(
		'section' => array(
			'model' => 'section',
			'foreign_key'  => 'section_id',
		),
		'page' => array(
			'model' => 'page',
			'foreign_key'  => 'page_id',
		),
		'parent' => array(
			'model' => 'menu',
			'foreign_key' => 'parent_id',
		),
	);

	protected $_has_many = array(
		'childs' => array(
			'model' => 'menu',
			'foreign_key' => 'parent_id',
		),
	);

	protected $_load_with = array('section', 'page', 'section:type', 'page:type');

	public function get_tree($parent_id = NULL)
	{
		if ($this->_cached === NULL) {
		    $parent = $this
				// ->where($this->_table_name.'.parent_id', (($parent_id === NULL) ? 'IS' : '='), $parent_id)
				->where($this->_table_name.'.showhide', '=', 1)
				->order_by('order')
				->find_all();
		    $_cached = $parent->as_array();

		    foreach ($_cached as $item) {
		    	if ( ! isset($this->_cached[$item->parent_id])) {
		    		$this->_cached[$item->parent_id] = array();
		    	}

		    	$this->_cached[$item->parent_id][] = $item;
		    }
		}

		return $this->_cached[$parent_id];
	}
}
