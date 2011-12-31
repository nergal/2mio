<?php
/**
 * Model_Answers
 * 
 * @author sokol
 * @packege btlady-admin
 */

class Model_Answers extends Model_Common
{
	public static $self; 
	private $tree;
	private $grid;
	
	public function __construct()
	{
		parent::__construct();
		self::$self = $this;
	}
	
	public function get_questions($dateFrom, $dateTo, $sp_id, $q_state)
	{
		$this->init_tree_grid();
		
		$sql = 
			" SELECT q.id, q.title, q.body, q.date, q.showhide, IFNULL(u.username, q.author) username, " .
				" count(a.id) ans_num, s.name s_name, 0 selected,  0 link, NULL q_parent_id, 'q' type_data, q.section_id sp_id, " .
				" q.name_url, s.name_url cat_name_url, q.user_id, q.rating, q.photo, 1 q_link, q.name_url q_name_url, s.name_url s_name_url, " .
				" '' q_url, 0 doctor_id, 0 answer " .
			" FROM questions q " .
			" LEFT JOIN answers a ON q.id = a.question_id " .
			" LEFT JOIN users u ON u.user_id = q.user_id " .
			" LEFT JOIN user_infos ui ON ui.id = u.user_id " .
			" LEFT JOIN specialities s ON s.id = q.section_id " .
			" WHERE (q.date BETWEEN '{$dateFrom}' AND '{$dateTo}') " .
			($sp_id ? " AND s.id = {$sp_id} " : "") .
			" GROUP BY q.id " .
			($q_state == 'withans' ? " HAVING count(a.id) > 0 " : "") .
			($q_state == 'noans' ? " HAVING count(a.id) = 0 " : "");
		
		$sql = "SELECT tbl.* FROM({$sql}) tbl WHERE 1 = 1";
		
		$this->upd_quest_sql = "UPDATE questions SET title = :title, body = :body, showhide= :showhide WHERE id = :id";
		$this->upd_ans_sql = "UPDATE answers SET body = :body, showhide = :showhide WHERE id = :id";
		$this->insert_ans_sql = "INSERT INTO answers (doctor_id, question_id, author, body, showhide, ip) VALUES (:d_id, :q_id, :author, :body, :showhide, :ip)";
		$this->delete_quest_sql = "DELETE FROM questions WHERE id = :id";
		$this->delete_ans_sql = "DELETE FROM answers WHERE id = :id";
		
		function beforeRender($action)
		{
			$self = Model_Answers::$self;
			
			if(!$action->get_value('ans_num'))
				$action->set_kids(false);
				
			$url = 'http://' . $self->domain . '/consult-' . $action->get_value('s_name_url') .
				'/question-' . $action->get_value('id') . '-' . $action->get_value('q_name_url') . '/';
				
			$action->set_value('q_url', $url);
			$action->set_value('link', "<a target=\"_blank\" href=\"{$url}\">просм.</a>");
		}
		
		function beforeUpdate($action)
		{
			$self = Model_Answers::$self;
			
			$title = $action->get_value('title');
			$body = $action->get_value('body');
			$id = $action->get_value('id');
			$showhide = $action->get_value('showhide');
			$answer = $action->get_value('answer');
			
			if($answer)
			{
				DB::query(Database::UPDATE, $self->upd_ans_sql)->parameters(array(':body' => $body, ':showhide' => $showhide, ':id' => $id))->execute();
			}
			else
			{
				DB::query(Database::UPDATE, $self->upd_quest_sql)->parameters(array(':title' => $title, ':body' => $body, ':showhide' => $showhide, ':id' => $id))->execute();
			}
			
			$action->success();
		}
		
		function beforeInsert($action)
		{
			$self = Model_Answers::$self;
			$d_id = $action->get_value('doctor_id');
			$q_id = $action->get_value('id');
			$author = $action->get_value('username');
			$body = $action->get_value('body');
			$showhide = $action->get_value('showhide');
			$ip = $_SERVER['REMOTE_ADDR'];
			
			$result = DB::query(Database::INSERT, $self->insert_ans_sql)->parameters(
				array(
					':d_id' => $d_id,
					':q_id' => $q_id,
					':author' => $author,
					':body' => $body,
					':showhide' => $showhide,
					':ip' => $ip
				)
			)->execute();
			
			if($result[0])
				$action->success('a' . $result[0]);
			else
				$action->error();
		}
		
		function beforeDelete($action)
		{
			$self = Model_Answers::$self;
			$id = $action->get_value('id');
			$answer = $action->get_value('answer');
			
			if($answer)
				$sql = $self->delete_ans_sql;
			else
				$sql = $self->delete_quest_sql;
				
			$affected_rows = DB::query(Database::DELETE, $sql)->parameters(array(':id' => $id))->execute();
			
			if($affected_rows)
				$action->success();
			else
				$action->error();
		}
		
		$this->tree->event->attach('beforeRender', 'beforeRender');
		$this->tree->event->attach('beforeUpdate', 'beforeUpdate');
		$this->tree->event->attach('beforeInsert', 'beforeInsert');
		$this->tree->event->attach('beforeDelete', 'beforeDelete');
		$this->tree->render_sql($sql, 'id', 'selected,id,ans_num,s_name,username,title,body,date,rating,link,showhide,photo,q_url,doctor_id,answer', '', 'q_parent_id');
	}
	
	public function get_answers($q_id)
	{
		$this->init_tree_grid();
		
		$sql = 
			" SELECT CONCAT('a', a.id) dhmtlx_id, a.id, a.date,  a.body,  '' title,  '-' ans_count, a.rating, a.showhide, GROUP_CONCAT(distinct s.name SEPARATOR ', ') specialty, a.photo, " .
				" 0 selected, CONCAT_WS(' ', d.sname, d.name, d.fname) docname, '-' link, a.question_id, '' q_url, a.doctor_id, 1 answer " .
			" FROM answers a " .
			" LEFT JOIN doctors d ON d.u_id = a.doctor_id " .
			" LEFT JOIN consultants c ON c.id = d.u_id " .
			" LEFT JOIN specialities s ON s.id = c.speciality_id " .
			" GROUP BY a.id";
			
		$sql = "SELECT tbl.* FROM({$sql}) tbl WHERE 1 = 1";
		$updateSQL = "UPDATE answers SET body = '{body}' WHERE id = {id}";
		
		function beforeRender($action)
		{
			$action->set_kids(false);	
		}
		
		function beforeUpdate($action)
		{
			$action->set_value('body', addslashes($action->get_value('body')));	
		}
			
		$this->tree->sql->attach('Update', $updateSQL);
		$this->tree->event->attach('beforeRender', 'beforeRender');
		$this->tree->event->attach('beforeUpdate', 'beforeUpdate');
		$this->tree->render_sql($sql, 'dhmtlx_id', 'selected,id,ans_count,specialty,docname,title,body,date,rating,link,showhide,photo,q_url,doctor_id,answer', '', 'tbl.question_id'); 
	}
	
	public function get_specialties()
	{
		$sql = "SELECT id, name FROM specialities ORDER by name";
		return $this->fetchAll($sql);
	}
	
	public function get_advisers()
	{
		$this->init_grid();
		
		$sql = 
			" SELECT c.id, CONCAT_WS(' ', d.sname, d.name, d.fname) name, u.user_email, GROUP_CONCAT(distinct s.name SEPARATOR ', ') specialty, 0 selected " .
			" FROM consultants c " .
			" LEFT JOIN doctors d ON d.u_id = c.id " .
			" LEFT JOIN specialities s ON s.id = c.speciality_id " .
			" LEFT JOIN users u ON u.user_id = c.id " .
			" GROUP BY c.id";
			
		$sql = "SELECT tbl.* FROM({$sql}) tbl WHERE 1=1";
			
		$this->grid->render_sql($sql, 'id', 'selected,name,user_email,specialty');
	}
	
	public function get_advisers_emails($u_ids)
	{
		$sql = "SELECT user_email FROM users WHERE user_id IN (" . implode(',', $u_ids) . ")";
		$emails = array();
		$result = $this->fetchAll($sql);
		if($result)
			foreach($result as $r)
				$emails[] = $r['user_email'];
				
		return $emails;
	}
	
	public function update_question_photo($photo, $q_id)
	{
		$sql = "UPDATE questions SET photo = :photo WHERE id = :q_id";
		return DB::query(Database::UPDATE, $sql)->parameters(array(':photo' => $photo, ':q_id' => $q_id))->execute();
	}
	
	public function update_answer_photo($photo, $ans_id)
	{
		$sql = "UPDATE answers SET photo = :photo WHERE id = :ans_id";
		return DB::query(Database::UPDATE, $sql)->parameters(array(':photo' => $photo, ':ans_id' => $ans_id))->execute();	
	}
	
	public function get_question_photo($q_id)
	{
		$q_id = intval($q_id);
		$sql = "SELECT photo FROM questions WHERE id = {$q_id}";
		$result = $this->fetchAll($sql);

		if($result)
			return $result[0]['photo'];
			
		return false;	
	}
	
	public function get_answer_photo($ans_id)
	{
		$ans_id = intval($ans_id);
		$sql = "SELECT photo FROM answers WHERE id = {$ans_id}";
		$result = $this->fetchAll($sql);
		
		if($result)
			return $result[0]['photo'];
			
		return false;
	}
	
	public function remove_question_photo($q_id)
	{
		$sql = "UPDATE questions SET photo = NULL WHERE id = :q_id";
		return DB::query(Database::UPDATE, $sql)->parameters(array(':q_id' => $q_id))->execute();
	}
	
	public function remove_answer_photo($ans_id)
	{
		$sql = "UPDATE answers SET photo = NULL WHERE id = :ans_id";
		return DB::query(Database::UPDATE, $sql)->parameters(array(':ans_id' => $ans_id))->execute();	
	}
	
	public static function clear_cache()
	{
		//	
	}
	
	private function init_tree_grid()
	{
		require($this->pathDhtmlx . 'treegrid_connector.php');
		
		$this->tree = new TreeGridConnector($this->connection);
        $this->tree->enable_log($this->connectorLog, true);
        $this->tree->dynamic_loading(50);
        $this->tree->event->attach('afterProcessing', array($this, 'clear_cache'));
	}
	
	private function init_grid()
	{
		require($this->pathDhtmlx . 'grid_connector.php');
		
		$this->grid = new GridConnector($this->connection);
		$this->grid->enable_log($this->connectorLog, true);
		$this->grid->dynamic_loading(50); 
	}
}
