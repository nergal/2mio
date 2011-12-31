<?php

class Model_Links extends ORM {
	
	/**
	 * Имя таблицы
	 * @var string
	 */
	protected $_table_name = 'links';	
	
	public function get_lasts($show = TRUE, $limit = 6)
	{
		
        $query = $this;
        if ($show === TRUE) {
        	$query = $query->where($this->_table_name.'.showhide', '=', 1);
        }
        $query = $query
			->order_by('sort')
			->limit($limit);

        return $query->find_all();
	}

}
