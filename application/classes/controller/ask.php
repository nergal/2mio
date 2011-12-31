<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Контроллер опроса
 * TODO: логику работы с БД нужно вынести в модель, переписать вместо sql - query builder
 * @author	 kolex
 * @package	btlady
 * @subpackage ask
 */
class Controller_Ask extends Controller_Abstract
{
	/**
	* Запись разделов
	*/
	public function action_setsections()
	{
		$this->response->headers('Content-Type', 'application/json; charset=utf-8');

		$result = array('status' => 'failed',);

		$gender = null;

		if ($this->request->is_ajax()) {

			$gender = (int)$this->request->post('radio');

			$checks = explode(',',$this->request->post('checks'));

			$sql = 'insert into ask_visitors (gender,ip) values(:gender, inet_aton(:ip))';
			$query = DB::query(Database::INSERT, $sql);
			$query->param(':gender', $gender);
			$query->param(':ip', Request::$client_ip);
			$data = $query->execute();
			$id = (int)$data[0];
			
			$sep = '';
			$sqlvals = '';
			foreach($checks as $check) {
				$sqlvals .= $sep.'('.$id.', '.$check.')';
				$sep = ',';
			}
			$sql = 'replace into ask_sections (visitor_id, section_id) values '.$sqlvals;
			$query = DB::query(Database::INSERT, $sql);
			$data = $query->execute();

			$result['status'] = 'success';
			$result['result'] = array('id'=>$id);
		}
		return $this->response->body(json_encode($result));
			
	}

	public function action_setsections_new()
	{
		$this->response->headers('Content-Type', 'application/json; charset=utf-8');

		$result = array('status' => 'failed',);

		$gender = null;

		if ($this->request->is_ajax()) {
			$gender = (int)$this->request->post('radio');
			$checks = explode(',',$this->request->post('checks'));

			$id = ORM::factory('ask')->setsections($gender, Request::$client_ip);

/*
			//var_dump($data);
		    if (empty($this->_primary_key_value))
		    {
		        // Set the primary key value if it was manually chosen by the user
		        $this->_primary_key_value = $this->_object[$this->_primary_key];
		    }
		    */
    	}
		return $this->response->body(json_encode($id));
	}

	/**
	* Запись email
	*/
	public function action_setemail()
	{
		$this->response->headers('Content-Type', 'application/json; charset=utf-8');

		$result = array('status' => 'failed',);

		if($this->request->is_ajax()) {

			$id	= $this->request->post('id');
			$email = $this->request->post('email');

			if(!empty($id) && !empty($email)){
				$sql   = 'update ask_visitors set email = :email where id = :id';
				$query = DB::query(Database::UPDATE, $sql);
				$query->param(':id', $id);
				$query->param(':email', $email);
				$result = $query->execute();
			}
		}	

		return $this->response->body(json_encode($result));
	}

	/**
	* Запись куки /
	* пишем куку сервером, т.к. через js неверно устаналивается path '/'
	*/
	public function action_setcookie()
	{
		$name = $this->request->post('name');
		if(!empty($name))
		{
			$expire = time()+3600*24*1000; // 1000 дней
			switch($name) {
				case 'enter': setcookie("ask_was_enter", 1, $expire, '/'); print 1; break;
				case 'asked': setcookie("ask_was_asked", 1, $expire, '/'); print 1; break;
			}
		}
	}

}

