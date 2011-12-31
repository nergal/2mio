<?php
/**
 * Модель сброса кэша
 *
 * @author nergal
 * @package btlady
 * @subpackage admin
 */

class Model_Cache extends Model_Common {
    public function get_tables()
    {
	$tables = DB::query(Database::SELECT, 'SHOW tables')->execute()->as_array();
	$tables = array_map(function($item) {
	    return reset($item);
	}, $tables);
	
	return $tables;
    }

    public function clear($id)
    {
        return Model_Common::$cache->delete_tag($id);
    }
}