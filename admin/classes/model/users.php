<?php
/**
 * Модель админка пользователей
 *
 * @author sokol
 * @package btlady-admin
 */
class Model_Users extends Model_Common
{
	public static $self;   

    public function __construct()
    {
        parent::__construct();
        require($this->pathDhtmlx . 'grid_connector.php');
		$this->grid = new GridConnector($this->connection);
		$this->grid->dynamic_loading(50); 
		$this->grid->enable_log($this->connectorLog, true);
		self::$self = $this;
    }

    public function get_all_users()
    {
		$selectSQL = 
			" SELECT user_id, IF(user_regdate IS NULL OR NOT user_regdate, '', FROM_UNIXTIME(user_regdate)) user_regdate, username, user_email, " .
			" IF(user_birthday IS NULL OR NOT user_birthday, '', STR_TO_DATE(user_birthday, '%d-%m-%Y')) user_birthday, " .
			" IF(user_lastvisit IS NULL OR NOT user_lastvisit, '', FROM_UNIXTIME(user_lastvisit)) user_lastvisit, user_avatar, user_sig, " .
			" user_from, user_occ, user_interests, user_facebook, user_vkontakte, '' password " .
			" FROM phpbb3.phpbb_users ";
			
		$selectSQL = "SELECT tbl.* FROM ({$selectSQL}) tbl WHERE 1=1";

		$updateSQL = 
			" UPDATE phpbb3.phpbb_users " .
			" SET user_regdate = IF('{user_regdate}', UNIX_TIMESTAMP(STR_TO_DATE('{user_regdate}','%Y-%m-%d %h:%i:%s')), user_regdate), " .
			" username = '{username}', " .
			" user_email = '{user_email}', " .
			" user_birthday = IF('{user_birthday}', '{user_birthday}', user_birthday), " .
			" user_lastvisit = IF('{user_lastvisit}', UNIX_TIMESTAMP(STR_TO_DATE('{user_lastvisit}','%Y-%m-%d %h:%i:%s')), user_lastvisit), " .
			" user_sig = '{user_sig}', " .
			" user_from = '{user_from}', " .
			" user_occ = '{user_occ}', " .
			" user_interests = '{user_interests}', " .
			" user_facebook = '{user_facebook}', " .
			" user_vkontakte = '{user_vkontakte}' " .
			//" user_password = IF('{password}', '{password}', user_password) " .
			" WHERE user_id = {user_id} ";
			
		function beforeUpdate($action)
		{
			$escape = array('username', 'user_sig', 'user_from', 'user_occ', 'user_interests', 'user_facebook', 'user_vkontakte');
			Model_Users::$self->remove_single_quote($escape, $action);
			
			//хак, если дата заканчивается на 00:00:00 STR_TO_DATE возвр. NULL
			$dates = array('user_regdate', 'user_birthday', 'user_lastvisit');
			foreach($dates as $date)
			{
				$value = str_replace('00:00:00', '01:00:00', $action->get_value($date));
				$action->set_value($date, $value);	
			}
			
			$birthdate = date('d-m-Y', strtotime($action->get_value('user_birthday')));
			$action->set_value('user_birthday', $birthdate);
		}
		
		$this->grid->sql->attach('Update', $updateSQL);		
		$this->grid->event->attach('beforeUpdate', 'beforeUpdate');
		$this->grid->render_sql($selectSQL, 'user_id', 'user_id,user_email,username,user_regdate,user_birthday,user_lastvisit,user_from,user_occ,user_sig,user_interests,user_avatar,user_facebook,user_vkontakte,password');
    }
    
    private function get_user_roles() 
    {
    	$sql = "SELECT name, description FROM roles";
    	$result = $this->fetchAll($sql);
    	$roles = array();
    	foreach($result as $r)
    	{
    		$roles[$r['name']] = $r['description'];	
    	}	
    	return $roles;
    }
    
    public function remove_single_quote($names, $action)
    {
		foreach($names as $name)
		{
			$value = str_replace("'", "\'", $action->get_value($name));
			$action->set_value($name, $value);
		}		
	}
	
	public function get_all_roles()
	{
		$this->grid->render_table('roles', 'id', 'description');
	}
	
	public function get_bind_roles($u_id)
	{
		$sql = "SELECT id, description, user_id FROM roles_users LEFT JOIN roles ON role_id = id WHERE 1=1";
		$inserSQL = "INSERT INTO roles_users (user_id, role_id) VALUES ({$u_id}, {id})";
		$deleteSQL = "DELETE FROM roles_users WHERE user_id = {$u_id} AND role_id = {id}";
		
		$this->u_id = $u_id;		
		function beforeFilter($action)
		{
			$action->add("user_id", Model_Users::$self->u_id, "=");
		}		
		
		function beforeInsert($action)
		{
			if($action->get_value('id') > 9999)
				$action->error();	
		}		
		
		$this->grid->sql->attach('Insert', $inserSQL);	
		$this->grid->sql->attach('Delete', $deleteSQL);			
		$this->grid->event->attach('beforeFilter', 'beforeFilter');
		$this->grid->event->attach('beforeInsert', 'beforeInsert');				
		$this->grid->render_sql($sql, 'id', 'description');	
	}
}
