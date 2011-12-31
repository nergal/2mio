<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Database query wrapper.  See [Prepared Statements](database/query/prepared) for usage and examples.
 *
 * @author kolex
 * @package btlady-admin
 */
class Database_Mysql extends Kohana_Database_MySQL {

	/**
	 * Get connection resource
	 *
	 * @param   void
	 * @return  mixed    connection resource
	 */
	public function get_connection()
	{
		return $this->_connection;
	}

} // End Database_Mysql
