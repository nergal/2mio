<?php
/**
 * Controller_Answers
 *
 * @author sokol
 * @package btlady-admin
 */
class Controller_Answers extends Controller_Abstract
{
	private $dateFrom;
	private $dateTo;
	private $img_to_upl = array('image/jpeg' => 'jpg', 'image/png' => 'png', 'image/gif' => 'gif');
	
	public function before()
	{
		parent::before();
		$this->view = View::factory('answers/index');
		$this->model = Model::factory('answers');
		$this->model->view = $this->view;
		$this->model->domain = self::$domain;
		$this->view->domain = self::$domain;
		
		$this->dateFrom = date('d-m-Y', mktime(0, 0, 0, date("m") - 3, date("d"), date("Y")));
		$this->dateTo = date('d-m-Y');
    }	
    
    public function action_index() 
    {
		$this->template = $this->view;
		$this->template->dateFrom = $this->dateFrom;
		$this->template->dateTo = $this->dateTo;
		$this->template->specialties = $this->model->get_specialties();
	}
	
	public function action_get_questions()
	{
		$dateFrom = $this->request->param('df', $this->dateFrom);
		$dateTo = $this->request->param('dt', $this->dateTo);
		$sp_id = $this->request->param('sp', 0);
		$q_state = $this->request->param('st', 'all');
		
		$regDate = '/^(\d{2}-\d{2}-\d{4})$/i';
		if(!preg_match($regDate, $dateFrom))
			$dateFrom = $this->dateFrom;
			
		if(!preg_match($regDate, $dateTo))
			$dateTo = $this->dateTo;
		
		list($day, $month, $year) = explode('-', $dateFrom);
		$dateFrom = date('Y-m-d', mktime(0, 0, 0, $month, $day, $year));
		
		list($day, $month, $year) = explode('-', $dateTo);
		$dateTo = date('Y-m-d', mktime(0, 0, 0, $month, $day, $year));
		
		$this->model->get_questions($dateFrom, $dateTo, $sp_id, $q_state);	
	}
	
	public function action_get_answers()
	{
		$q_id = (int) (isset($_GET['id']) ? $_GET['id'] : 0);	
		$this->model->get_answers($q_id);
	}
	
	public function action_get_advisers()
	{
		$this->model->get_advisers();	
	}
	
	public function action_send_letter()
	{
		$title = $this->request->post('title');
		$body = $this->request->post('body');
		$u_ids = explode(',', $this->request->post('to'));
		$u_ids = array_filter($u_ids, function($elem){return strval(intval($elem)) == strval($elem) && intval($elem);});	
		$result = false;
		
		$emails = $this->model->get_advisers_emails($u_ids);
		
		if($emails && $title && $body)
		{
			event::emit('consult_notify_adv', array('to' => $emails, 'title' => $title, 'body' => $body));
			$result = true;	
		}
		
		print json_encode($result);
	}
	
	public function action_upload_photo()
	{
		$item_id = $this->request->post('item_id');
		$answer = $this->request->post('ans');
		$result = array('msg' => 'Не удалось загрузить фото', 'result' => false);

		if($item_id && isset($_FILES['photo']))
		{
			$filename = '';
			try {
				$filename = $this->upload($_FILES['photo']['tmp_name']);
			} catch (Kohana_Exception $e) {
				$result = array('msg' => $e->getMessage(), 'result' => false);
			}
			
			if($filename)
			{
				$updated = false;
				if($answer)
					$updated = $this->model->update_answer_photo($filename, $item_id);
				else
					$updated = $this->model->update_question_photo($filename, $item_id);
					
				if($updated)
					$result = array('msg' => 'Фото успешно загружено', 'result' => true);
			}
		}
		
		print json_encode($result);	
	}
	
	public function action_get_photo()
	{
		$item_id = $this->request->post('item_id');
		$answer = $this->request->post('ans');
		$photo = '';
		
		if(!$answer)
			$photo = $this->model->get_question_photo($item_id);
		else
			$photo = $this->model->get_answer_photo($item_id);
			
		print json_encode($photo);
	}
	
	public function action_remove_photo()
	{
		$item_id = $this->request->post('item_id');
		$answer = $this->request->post('ans');	
		
		$result = false;
		if(!$answer)
			$result = $this->model->remove_question_photo($item_id);
		else
			$result = $this->model->remove_answer_photo($item_id);
			
		if($result)
			$result = array('result' => true, 'msg' => 'Фото успешно удалено');
		else
			$result = array('result' => false, 'msg' => 'Не удалось удалить фото');
			
		print json_encode($result);
	}
	
    private function upload($filename)
    {
		$photo_path = realpath(DOCROOT . '/uploads/');
		$parts = getimagesize($filename);
		
		if(!$photo_path)
			throw new Kohana_Exception('Путь сохранения фотографии не найден');
			
		if(!isset($this->img_to_upl[$parts['mime']]))
			throw new Kohana_Exception('Изображение должно быть в формате: ' . implode(', ', $this->img_to_upl));
    	
		$fname = md5_file($filename) . '.' . $this->img_to_upl[$parts['mime']];
		$path = $photo_path . '/' . substr($fname, 0, 2) . '/' . substr($fname, 2, 4);
		
		if(!realpath($path))
			mkdir($path, 0775, true);
			
		$path = realpath($path);
		if(!$path)
			throw new Kohana_Exception('Неправильный путь сохранения фотографии');
			
		if(move_uploaded_file($filename, $path . '/' . $fname))
			return $fname;
			
		return false;
    }
}
