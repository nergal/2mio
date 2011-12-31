<?php

/**
 * Общая логика моделей админки
 *
 * @author kolex
 * @package btlady-admin
 */
class Model_Common
{
	protected $pathDhtmlx;
	protected $connectorLog;
	protected $db;
	static $cache;
	
    /**
     * Инициализация модели
     */
	public function __construct()
	{
        $this->pathDhtmlx   = APPPATH . '../public/js/dhtmlx/';
        $this->connectorLog = APPPATH . '../etc/logs/admin.log';
        
		$this->db = Database_Mysql::instance();
		$this->db->connect();
		$this->connection = $this->db->get_connection();
		
		Model_Common::$cache = Cache::instance('memcache'); 
	}
	
    /**
     * Получение ID из урла коннектора
     */
    public function extractIdFromUrl($url)
    {
        $id = null;

        $param = explode('=', strstr($url,'?')); //$this->request->uri()
        
        if(isset($param[1]))
        {
            $id = $param[1];
        }

        return $id;
    }
	
	public function fetchAll($query)
	{
		return $this->db->query(Database::SELECT, $query)->as_array();
	}
}
