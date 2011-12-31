<?php
/**
 * Controller_Consult
 *
 * @author sokol
 * @package btlady-admin
 */
class Controller_Advisers extends Controller_Abstract
{
	private $months = array(
		'1'=>array('label'=>'Январь','color'=>'AFD8F8'),
		'2'=>array('label'=>'Февраль','color'=>'F6BD0F'),
		'3'=>array('label'=>'Март','color'=>'8BBA00'),
		'4'=>array('label'=>'Апрель','color'=>'FF8E46'),
		'5'=>array('label'=>'Май','color'=>'008E8E'),
		'6'=>array('label'=>'Июнь','color'=>'D64646'),
		'7'=>array('label'=>'Июль','color'=>'8E468E'),
		'8'=>array('label'=>'Август','color'=>'588526'),
		'9'=>array('label'=>'Сентябрь','color'=>'B3AA00'),
		'10'=>array('label'=>'Октябрь','color'=>'008ED6'),
		'11'=>array('label'=>'Ноябрь','color'=>'9D080D'),
		'12'=>array('label'=>'Декабрь','color'=>'A186BE')
	);
	
	private $img_to_upl = array('image/jpeg' => 'jpg', 'image/png' => 'png', 'image/gif' => 'gif');
	
	public function before()
	{
		parent::before();
		$this->view = View::factory('advisers/index');
		$this->model = Model::factory('advisers');
    }
	
	public function action_index()
	{
		$this->template = $this->view;
		$this->template->specialties = $this->model->getAllSpecialties();
		$this->template->domain = self::$domain;
		$this->template->countries = $this->model->getCountries();
	}
	
	public function action_advisers()
	{
		$year = (int) $this->request->param('year', 0);
		$month = (int) $this->request->param('month', 0);
		$sp_id = (int) $this->request->param('sp_id', 0);
		$ans = $this->request->param('ans', 'all');
		
		$this->model->getAdvisersList($year, $month, $sp_id, $ans);
	}
	
	public function action_answers($adv_id)
	{
		$adv_id = intval($adv_id);
		$this->model->getAdviserAnswers($adv_id);
	}
	
	public function action_yearchart($adv_id)
	{
		$year = (int) $this->request->param('year', 0);
		$sp_id = (int) $this->request->param('sp_id', 0);
		$adv_id = (int) $this->request->param('adv_id', 0);
		
		$data = array();
		if($adv_id)
		{
			$data = $this->model->getAnswersByMonth($year, $adv_id, $sp_id);
		}

		$answers = array();
		for($i = 1; $i <= 12; $i ++)
		{
			$month_data = current($data);
			$answers[] = array('label' => $this->months[$i]['label'], 'value' => ($i == $month_data['month'] ? $month_data['ans_count'] : 0));
			if($i == $month_data['month'])
				array_shift($data);
		}

		$chart = array(
			'chart' => 
				array(
					'caption' => 'Ответы консультанта' . ($year ? ' за год ' . $year : ''),
					'xAxisName' => 'Месяц',
					'yAxisName' => 'Ответы',
					'formatNumberScale' => 0
				),
			'data' => $answers
		);
		
		print json_encode($chart);
	}
	
	public function action_sendletters()
	{
		$title = $this->request->post('title');
		$text = $this->request->post('text');
		$to = $this->request->post('to');
		$result = false;
		
		if($title && $text && $to)
		{
			$emails = $this->model->getAdvisersEmailsById($to);
			
			if($emails)
			{
				event::emit('letter_to_advisers', array('to' => $emails, 'title' => $title, 'body' => $text));
				$result = true;
			}
		}
		
		print json_encode($result);
	}
	
	public function action_cities_by_country($country_id)
	{
		$country_id = (int) $country_id;
		$cities = $this->model->getCityByCountryId($country_id);
		
		print json_encode($cities);
	}
	
	public function action_uploadphoto()
	{
		$u_id = (int) $this->request->post('adv_id');
		$result = array('result' => false, 'msg' => 'Ошибка: не удалось загрузить фотографию');
		
		if(isset($_FILES['photo']) && $u_id)
		{
			try 
			{
				$fname = $this->upload($_FILES['photo']['tmp_name'], '/uploads/');
				if($fname)
				{
					if($this->model->updateAdvPhoto($fname, $u_id))
						$result = array('result' => true, 'msg' => 'Фото успешно загружено');
					else
						$result = array('result' => false, 'msg' => 'Ошибка: не удалось обновить фото');
				}
			} 
			catch(Kohana_Exception $e) 
			{
				$result = array('result' => false, 'msg' => 'Ошибка: ' . $e->getMessage());
			}
		}
		
		print json_encode($result);
	}
	
	public function action_get_adv_photo()
	{
		$u_id = (int) $this->request->post('adv_id');
		$photo = false;
		
		if($u_id)
		{
			$photo = $this->model->getAdvPhoto($u_id);
		}
		
		print json_encode($photo);
	}
	
	public function action_rm_adv_photo()
	{
		$u_id = (int) $this->request->post('adv_id');
		$result = array('result' => false, 'msg' => 'Ошибка: не удалось удалить фотографию');
		
		if($u_id)
		{
			if($this->model->updateAdvPhoto(NULL, $u_id))
				$result = array('result' => true, 'msg' => 'Фото успешно удалено');
		}
		
		print json_encode($result);
	}
	
	public function action_get_diploma_photos()
	{
		$u_id = (int) $this->request->post('adv_id');
		$diplomas = array();
		
		if($u_id)
			$diplomas = $this->model->getAdvDiplomas($u_id);
		
		print json_encode($diplomas);
	}
	
	public function action_upload_diploma_photo()
	{
		$u_id = (int) $this->request->post('adv_id');
		$result = array('result' => false, 'msg' => 'Ошибка: не удалось загрузить фото');

		if(isset($_FILES['photo']) && $u_id)
		{
			try 
			{
				$fname = $this->upload($_FILES['photo']['tmp_name'], '/uploads/diplomas/');
				if($fname)
				{
					if($this->model->addDiplomaPhoto($fname, $u_id))
						$result = array('result' => true, 'msg' => 'Фото успешно загружено');
				}
			} 
			catch(Kohana_Exception $e) 
			{
				$result = array('result' => false, 'msg' => 'Ошибка: ' . $e->getMessage());
			}
		}
		
		print json_encode($result);
	}
	
	public function action_rm_diploma_photo()
	{
		$u_id = (int) $this->request->post('adv_id');
		$ph_id = (int) $this->request->post('ph_id');
		$result = array('result' => false, 'msg' => 'Ошибка: не удалось Удалить фото');
		
		if($u_id && $ph_id)
		{
			if($this->model->rmDiplomaPhoto($u_id, $ph_id))
				$result = array('result' => true, 'msg' => 'Фото успешно удалено', 'ph_id' => $ph_id);
		}
		
		print json_encode($result);
	}
	
    private function upload($filename, $sub_path)
    {
		$photo_path = realpath(DOCROOT . $sub_path);
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
