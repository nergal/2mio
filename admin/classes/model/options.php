<?php
/**
 * Model_Options 
 *
 * @author sokol, 2011
 * @package btlady-admin
 */
class Model_Options extends Model_Common 
{
	private $grid;
	private $datatype_to_dhtmlx;
	private $datatype_to_table;
	private $value_select_func = array(
		'ip' => ' INET_NTOA(value) ',
		'date-event' => " FROM_UNIXTIME(value, GET_FORMAT(DATE,'EUR')) "
	);
	private $value_change_func = array(
		'datetime' => " UNIX_TIMESTAMP(STR_TO_DATE(:value,'%d.%m.%Y')) ",
		'ip' => ' INET_ATON(:value) '
	);
	public static $self;
	
	public function __construct()
	{
		parent::__construct();
		
		if(!class_exists('GridConnector'))
			require($this->pathDhtmlx . 'grid_connector.php');
			
		$this->grid = new GridConnector($this->connection);
		$this->grid->enable_log($this->connectorLog, true);
		
		$this->init_dhtmlx_datatype();
		self::$self = $this;
	}
	
	public function init($datatype_to_table)
	{
		$this->datatype_to_table = $datatype_to_table;
	}
	
	public function get_options($table_name, $page_id)
	{
		$used_options = $this->get_used_types($table_name);
		$select_sql = $this->get_select_sql($used_options, $page_id, $table_name);

		$insertSQL = "INSERT INTO options_types (label, name, type_id, table_name) VALUES ('{label}', '{name}', {type_name}, '{$table_name}')";
		$deleteSQL = "DELETE FROM options_types WHERE id = {id}";
		$updateSQL = "UPDATE options_types SET label = '{label}', name = '{name}' WHERE id = {id}";
		
		function beforeRender($action)
		{
			$action->set_cell_attribute('value', 'type', $action->get_value('type'));
		}
		
		function beforeInsert($action)
		{
			$types = Model_Options::$self->get_type_values();
			$type_id = 0;
			foreach($types as $type)
			{
				if($type['alias'] == $action->get_value('type_name'))
				{
					Model_Options::$self->insert_type_name = $action->get_value('type_name');
					$type_id = $type['id'];
					break;
				}
			}
			$action->set_value('type_name', $type_id);
		}
		
		$this->page_id = $page_id;
		function afterInsert($action)
		{
			$status = $action->get_status();
			$insert_id = mysql_insert_id();
			if($status == 'inserted')
			{
				$type = Model_Options::$self->insert_type_name;
				$page_id = Model_Options::$self->page_id;
				$option_id = $insert_id;
				$value = $action->get_value('value');
				$result = Model_Options::$self->set_option_value($type, $page_id, $option_id, $value);
				
				if($result)
					$action->success($insert_id);
				else
					$action->error();
			}
		}
		
		function beforeSort($action)
		{
			$action->add('id', 'ASC');
		}
		
		function afterUpdate($action)
		{
			if($action->get_status() == 'updated')
			{
				$type = $action->get_value('alias');
				$label = $action->get_value('label');
				$page_id = Model_Options::$self->page_id;
				$option_id = $action->get_value('id');;
				$value = $action->get_value('value');
				$result = Model_Options::$self->set_option_value($type, $page_id, $option_id, $value, $label);
				
				if($result)
					$action->success();
				else
					$action->error();
			}
		}
		
		$this->grid->event->attach('beforeSort', 'beforeSort');
		$this->grid->event->attach('beforeRender', 'beforeRender');		
		$this->grid->event->attach('afterInsert', 'afterInsert');
		$this->grid->event->attach('beforeInsert', 'beforeInsert');
		$this->grid->event->attach('afterUpdate', 'afterUpdate');
		$this->grid->sql->attach("Insert", $insertSQL);
		$this->grid->sql->attach("Delete", $deleteSQL);
		$this->grid->sql->attach("Update", $updateSQL);
		$this->grid->render_sql($select_sql, 'id', 'label, name, value, type_name, alias');
	}
	
	/**
	 * ���������� ������ ��� ���� - ��� ������, �������� - ������ type_id
	 *
	 * @param string $table_name
	 * @return array
	 */
	private function get_used_types($table_name)
	{
		$types = array();
		$table_name = $this->db->escape($table_name);
		
		$sql = "
			SELECT tv.alias, ot.type_id
			FROM options_types ot
			LEFT JOIN type_values tv ON tv.id = ot.type_id
			WHERE ot.table_name = {$table_name}
			ORDER BY tv.alias";
			
		$result = $this->fetchAll($sql);

		if($result)
		{
			foreach($this->datatype_to_table as $type_name => $value)
			{
				$temp = array();
				foreach($result as $type)
				{
					if($type['alias'] == $type_name)
						$temp['type_id'] = $type['type_id'];
				}		
				
				if($temp)
					$types[$type_name] = $temp;
			}
		}
		
		return $types;
	}
	
	private function get_select_sql($used_options, $page_id, $table_name)
	{
		$page_id = intval($page_id);
		$table_name = $this->db->escape($table_name);
		$sql = '';
		
		if(!$used_options)
		{
			$key = key($this->datatype_to_table);
			$temp[$key] = array(-1);
			$used_options = $temp;
		}

		foreach($used_options as $type => $options)
		{
			if($sql) $sql .= ' UNION ';
			$sql .= " (SELECT ot.id, " . $this->value_as_func() . ", ot.label, ot.name, '" . $this->datatype_to_dhtmlx[$type] . "' type, tv.name type_name, tv.alias " .
				" FROM options_types ot " . 
				" LEFT JOIN " . $this->datatype_to_table[$type] . " vt ON vt.option_id = ot.id AND vt.page_id = {$page_id}" .
				" LEFT JOIN type_values tv ON tv.id = ot.type_id " .
				" LEFT JOIN pages_type_options pto ON pto.option_id = ot.id " .
				" LEFT JOIN pages p ON p.type_id = pto.page_type_id " .
				" WHERE ot.type_id = {$options['type_id']} AND ot.table_name = {$table_name} " . 
					" AND p.id = {$page_id} AND pto.option_id IS NOT NULL )";
		}
		
		$sql = "SELECT tbl.* FROM ( " . $sql . " ) tbl ";
		
		return $sql;
	}
	
	public function set_option_value($type, $page_id, $option_id, $value, $label = null)
	{
		$deleteSQL = "DELETE FROM " . $this->datatype_to_table[$type] . " WHERE page_id = :page_id AND option_id = :option_id";
		$query = DB::query(Database::DELETE, $deleteSQL);
		$query->parameters(array(
			':page_id' => $page_id,
			':option_id' => $option_id
		)); 
		$query->execute();
		
		$str_value = ':value';
		if(isset($this->value_change_func[$type]))
			$str_value = $this->value_change_func[$type];
		else if($label && isset($this->value_change_func[$label]))
			$str_value = $this->value_change_func[$label];
			
		$insertSQL = "INSERT INTO " . $this->datatype_to_table[$type] . 
			" (page_id, option_id, value) VALUES  (:page_id, :option_id, {$str_value} )";
		$query = DB::query(Database::INSERT, $insertSQL);
		$query->parameters(array(
			':page_id' => $page_id,
			':option_id' => $option_id,
			':value' => $value
		));

		$affected_rows = $query->execute();
		if($affected_rows > 0)
			return true;
			
		return false;
	}
	
	private function value_as_func()
	{
		$sql = ' value ';
		if($this->value_select_func)
		{
			$sql = " CASE alias WHEN 'datetime' THEN " . $this->value_select_func['date-event'] . " ELSE ";
				$sql .= " CASE label ";
				foreach($this->value_select_func as $label => $func)
				{
					$sql .= " WHEN '{$label}' THEN {$func} "; 
				}
				$sql .= " ELSE value END ";
			$sql .= " END value ";
		}
		
		return $sql;
	}
	
	public function get_type_values()
	{
		$sql = "SELECT id, name, alias, dhtmlx FROM type_values";
		
		if(!isset($this->type_values))
			$this->type_values = $this->fetchAll($sql);
			
		return $this->type_values;
	}
	
	private function init_dhtmlx_datatype()
	{
		$type_values = $this->get_type_values();
		$this->datatype_to_dhtmlx = array();
		foreach($type_values as $type)
		{
			$this->datatype_to_dhtmlx[$type['alias']] = $type['dhtmlx'];
		}
	}
}
