<?php

/**
 * Контроллер материалов
 *
 * @author kolex
 * @package btlady-admin
 */
class Controller_Pages extends Controller_Abstract
{
	public static $base = null;
	private $Options;
	
	/**
	 * Инициализация 
	 */
	public function before()
	{
		parent::before();
		self::$base = parent::$base;
		self::$domain = parent::$domain;
		$this->obj = $this->request->controller();
		$this->pictureFolder = 'images/articles/';
		$this->galleryFolder = 'uploads/';
		$this->thumbnailsFolder = 'thumbnails/';
		$this->photoblank = '/i/default.gif';
		$this->Options = new Options();
		if ($this->request->is_initial()) {
		    Asset::add_css(array(
				'/css/admin/pages.css',
		    ));
		}
	}

	/**
	 * Запрос формы материалов
	 */
	public function action_index()
	{
		$this->template = View::factory('pages/index');
		$this->template->base = parent::$base;
		$this->template->domain = parent::$domain;
		$this->template->obj = $this->obj;
		$this->template->typedoc = 'pages';
		$this->template->pictureFolder = $this->pictureFolder;
		$this->template->option_type_values = $this->Options->get_type_values();
		
		//подгонка превью
		$Thumbnail = new Thumbnail();
		$this->template->preview_sizes = $Thumbnail->get_image_sizes();
		$this->template->resize_types = $Thumbnail->get_resize_types();
		if($this->request->post('create_thumbnail'))
		{
			$result = $Thumbnail->create_thumbnail($this->request);
			$this->response->body(json_encode($result));
			$this->template = null;
		}
	}
	
	/**
	 * Получение разделов
	 */
	public function action_sections()
	{
		Model::factory('pages')->get_sections($this->request);
	}

	/**
	 * Получение списка материалов по текущему разделу
	 */
	public function action_list($id = null)
	{
		Model::factory('pages')->get_pages($id);
	}
	
	/**
	 * Получить текстовый контент материала, ajax
	 * 
	 * @param integer $id
	 */
	function action_getcontent($id=null) 
	{ 
		if(isset($_POST))
		{
			print Model::factory('pages')->get_content($_POST['id']);
		}
	}
	
	/**
	 * Записать текстовый контент материала, ajax
	 * 
	 * @param integer $id
	 */
	function action_setcontent($id=null) 
	{ 
		if(isset($_POST))
		{
			print Model::factory('pages')->set_content($_POST);
		} else 
			print null; 
	}
	
	/**
	 * Получение всех тэгов по материалам
	 */
	public function action_alltags() 
	{
		Model::factory('pages')->get_alltags();
	}

	/**
	 * Получение тэгов по материалу
	 */
	public function action_pagetags($id = 0) 
	{
		Model::factory('pages')->get_pagetags($id);
	}
	
	/**
	 * Получение комментариев по материалу
	 */
	public function action_comments($id = 0) 
	{
		Model::factory('pages')->get_comments($id);
	}

	/**
	 * Существуют ли дополнительные превьюхи для выбора
	 * @param string $photo - относительный путь к основной превьюхе
	 */
	private function _preview_exists($photo)
	{
		if (!empty($photo)) {
			$photo_path = rtrim(DOCROOT, '/') . substr($photo, 0, strlen($photo) - 4) . "_1.png";
			if (file_exists($photo_path)) {
				return true;
			}
		}
		return false;
	}

	function getPhotoPath($id)
	{
	    //формируем путь в зависимости от типа материала
	    $photo = Model::factory('pages')->get_defaultphoto($id);
	    if($photo['photo'])
	    {
		switch((int)$photo['type_id'])
		{
		    case 5: $photo = '/uploads/'.$this->get_hash_subdirs($photo['photo']).$photo['photo']; break;
		    case 3: $photo = '/thumbnails/' . substr($photo['photo'], 0, 2) . '/' . substr($photo['photo'], 2, 4) . 
			'/' . str_replace('.flv', '.png', $photo['photo']); break;
		    default: $photo = '/'.$this->pictureFolder.$photo['photo'];
		}
		return $photo;
	    }
	    else
	    {
			unset($photo);
			return false;
	    }
	}

	/**
	 * Выводим форму аплоада фото
	 * @param $postid
	 * @param $photoid
	 */
	function action_photoform($id = 0, $photoid = 0, $is_gallery) 
	{
		if($id > 0) 
		{
			$this->template = View::factory('pages/upload');
			if($photoid > 0)
			{
				$photo = Model::factory('pages')->get_photo($photoid);
				$this->template->page_media = true;
			} else 
			{
				$photo = $this->getPhotoPath($id);
				$this->template->page_media = false;
			}
			
			if($is_gallery)
			{
				$this->template->photo_src = $photo;
				$gpath = str_replace('images', 'thumbnails', $photo);
				$gpath = explode(DIRECTORY_SEPARATOR,	$gpath);
				$fname = array_pop($gpath);
				$cropg = ($fname!='default.gif')?'cropg_620x400':'';
				$gpath = array_merge($gpath, array($cropg, $fname));
				$photo = implode(DIRECTORY_SEPARATOR, $gpath);
				$this->template->resize_msg = 'Кликните чтобы подобрать превью';
				$this->template->isgallery = true;
			}

			$this->template->pageid  = $id;
			$this->template->photoid = $photoid;
			$this->template->photo   = (!empty($photo)) ? $photo : null;
			$this->template->action  = 'getform';
			$this->template->base = parent::$base;
			$this->template->obj  = $this->obj;
			$this->template->script = '';
			
			$this->template->preview_ok = $this->_preview_exists($photo);
			
			$photo = Model::factory('pages')->get_defaultphoto($id);
			
			if ($photo['type_id'] == 3) {
				$this->template->is_video  = TRUE;
			}
			
		} else {
			print formatError('Вы должны выбрать записи материала и фото');  			
  		}
	}
	
	/**
	 * Добавление фото, видео по-умолчанию (превью)
	 */
	function action_uploadphoto() {
		
		$this->template = View::factory('pages/upload');
		$this->template->base = parent::$base;
		$this->template->obj = 'pages';
		$this->template->pageid  = $_POST['pageid'];
		$this->template->photoid = $_POST['photoid'];
		$this->template->page_media = false;
		$this->template->script = '';
		
		if(isset($_POST['preview_num']) && $_POST['preview_num'] != -1)
		{
			$preview_num = (int)$_POST['preview_num'];
			
			$photo = $this->getPhotoPath($_POST['pageid']);
			$this->template->preview_ok = $this->_preview_exists($photo);
			
			$this->template->photo = $photo;
			
			$photo_path = DOCROOT.ltrim($photo, '/');
			
			$source_photo_path = substr($photo_path, 0, strlen($photo_path) - 4)."_".(int)$preview_num.".png";
			copy($source_photo_path, $photo_path);
			
			$this->_clear_cropr($photo_path);
			
			$this->template->action  = 'getform';
			
			$photo = Model::factory('pages')->get_defaultphoto((int)$_POST['pageid']); 
			
			if ($photo['type_id'] == 3) {
				$this->template->is_video  = TRUE;
			}

			return;
		}

		if(isset($_POST['but_regenerate']))
		{
			// flv видеофайл
			$flv = Model::factory('pages')->get_defaultphoto((int)$_POST['pageid']); 
			$flv_name = $flv['photo'];
			
			// относительный путь к png превьюхе
			$photo_path = $this->getPhotoPath($_POST['pageid']);
			
			// путь к flv видео
			$flv_relative_path = $this->galleryFolder . substr($flv_name, 0, 2) . '/' . substr($flv_name, 2, 4) . '/';
			$flv_real_path = DOCROOT . $flv_relative_path;			
			
			$video = new ffmpeg_movie($flv_real_path . $flv_name);
			if(!$video)
			{
				$this->template->error = "Файл {$fileName} не является видео";
				return;
			} else {
				$preview_dir = DOCROOT . $this->thumbnailsFolder . substr($flv_name, 0, 2) . '/' . substr($flv_name, 2, 4) . '/';
				$preview_name = substr($flv_name, 0, strlen($flv_name) - 3) . 'png';
				for ($j = 0; $j < 10; $j++) {
					$this->_get_video_preview($video, $preview_dir, $preview_name, $j); // нарезка превьюх для выбора
				}
				//$this->_get_video_preview($video, $preview_dir, $preview_name); // главная превьюха
			}
			
			$photo = $this->getPhotoPath($_POST['pageid']);
			$this->_clear_cropr($photo_path);
			
			// for template
			$this->template->preview_ok = $this->_preview_exists($photo);
			
			$this->template->photo = $photo;
			$this->template->action  = 'getform';
			if ($flv['type_id'] == 3) {
				$this->template->is_video  = TRUE;
			}			
			
			return; 
		}
		
		if(isset($_POST['but_delete']))
		{
			$photo_id = (int)$_POST['photoid'];
			$page_id = (int)$_POST['pageid'];
			if(isset($_POST['page_media']))
				Model::factory('pages')->delete_page_media($photo_id);
			else {
				@unlink(DOCROOT . $this->getPhotoPath($page_id));
				for ($j = 0; $j < 10; $j++) {
					@unlink(DOCROOT . $this->getPhotoPath($page_id)."_$j.png");
				}
				
				Model::factory('pages')->delete_photo($page_id);
			}

			$this->template->photo  = $this->photoblank;
			$this->template->action = 'delete';
			return;
		}
		
		if(isset($_POST['pageid']) && strlen($_POST['pageid']) > 0 && isset($_POST['photoid']) && strlen($_POST['photoid']) > 0) {
			
			$photo = Model::factory('pages')->get_defaultphoto((int)$_POST['pageid']); 

			$fileName = $_FILES['upfile']['name'];
			$tempLoc  = $_FILES['upfile']['tmp_name'];
			$parts	= explode('.', $fileName);			
			$fileExt  = end($parts);
		  
			if(strlen($_FILES['upfile']['error']) > 0 && $_FILES['upfile']['error'] != 0)
			{
				$this->template->error = $_FILES['upfile']['error'];
				return;
			}
	
			$name = $_POST['pageid'] . "_" . $_POST['photoid'];
			$fname = $name . '.' . $fileExt;
				
			$relative_path = $this->pictureFolder . $fname;	   
			$target_path = DOCROOT . $relative_path; 
			
			if((int)$photo['type_id'] == 5 || (int)$photo['type_id'] == 3)
			{ 
				$fname = md5_file($tempLoc) . '.' . $fileExt;
				$relative_path = $this->galleryFolder . substr($fname, 0, 2) . '/' . substr($fname, 2, 4) . '/';
				$target_path = DOCROOT . $relative_path;
				if(!file_exists($target_path))
					mkdir($target_path, 0775, true);
				$relative_path .= $fname;
				$target_path .= $fname;
				
				//видео
				if((int)$photo['type_id'] == 3)
				{
					if(strtolower($fileExt) != 'flv')
					{
						$this->template->error = 'Видео должно быть в формате FLV';
						return;
					}
					else
					{
						$fname = md5_file($tempLoc) . '.flv';
						$preview_name = md5_file($tempLoc) . '.png';
						
						$video = new ffmpeg_movie($tempLoc);
						if(!$video)
						{
							$this->template->error = "Файл {$fileName} не является видео";
							return;
						}
						else
						{
							$preview_dir = DOCROOT . $this->thumbnailsFolder . substr($fname, 0, 2) . '/' . 
								substr($fname, 2, 4) . '/';
							$relative_path = $this->thumbnailsFolder . substr($fname, 0, 2) . '/' . 
								substr($fname, 2, 4) . '/' . $preview_name;
								
							if(!file_exists($preview_dir))
								mkdir($preview_dir, 0775, true);
							
							for ($j = 0; $j < 10; $j++) {
								$this->_get_video_preview($video, $preview_dir, $preview_name, $j); // нарезка превьюх для выбора
							}
							
							$this->_get_video_preview($video, $preview_dir, $preview_name); // основная превьюха
							
						}
					}
				}
			}
			
			if(move_uploaded_file($tempLoc, $target_path)) 
			{
				//если фото по-умолчанию 
				if((int)$_POST['photoid'] == 0) 
				{
					Model::factory('pages')->set_photo((int)$_POST['pageid'], $fname);
					$this->template->action = 'upload';
					$this->template->script = '<script>parent.pages.setDefaultPhotoFromForm("'.$_POST['pageid'].'","'.$fname.'");</script>';
					$this->template->is_video  = TRUE;
				} else 
				{
					$this->template->script = '<script>
						parent.pages.setPhotoFromForm("'.$_POST['photoid'].'","/'.$relative_path.'");
						parent.pages.winPhotoUpload.attachURL("/admin/pages/photoform/id/'.(int)$_POST['pageid'].'/photoid/'.$_POST['photoid'].'/gallery/1/");
					</script>';
				}
				$this->template->photo = '/' . $relative_path;
				$photo = $this->getPhotoPath($_POST['pageid']);
				$this->clear_article_cropr($photo);
				$this->template->preview_ok = $this->_preview_exists($photo);
			}
		
   		}// else echo 'Error uploading';
	}
	
	/**
	 * Зачистка кропов превью статьи
	 * @param $photo_path - путь к превьюхе (c именем файла)
	 */	
	private function clear_article_cropr($photo)
	{
		$photo_file = basename($photo);
		$photo_dir = DOCROOT.'thumbnails/articles';
		if ($dh = opendir($photo_dir)) {
			while (false !== ($dir = readdir($dh))) {
				$full_dir = $photo_dir.'/'.$dir;
				if (is_dir($full_dir) && $dir !== '.' && $dir !== '..') {
					if (strpos($dir, 'cropr') == 0) {
						try {
							$full_path = $full_dir.'/'.$photo_file;
							if(file_exists($full_path) && is_writable($full_path)) {
								unlink($full_path);
							}
						} catch (Exception $exc) {
							echo $exc->getTraceAsString();
						}
					}
	           }
	       } 
	       closedir($dh);
		}			
	}	

	/**
	 * Зачистка кропов данной превьюшки
	 * @param $photo_path - путь к превьюхе (c именем файла)
	 * $photo_path $photo_path - имя файла превьюхи
	 */	
	private function _clear_cropr($photo_path)
	{
		$photo_dir = dirname($photo_path);
		$photo_file = basename($photo_path);
		if ($dh = opendir($photo_dir)) {
			while (false !== ($dir = readdir($dh))) {
				$full_dir = $photo_dir.'/'.$dir;
				if (is_dir($full_dir) && $dir !== '.' && $dir !== '..') {
					if (strpos($dir, 'cropr') == 0) {
						$photo_file_name = basename($photo_path);
						@unlink($full_dir.'/'.$photo_file);
					}
	           }
	       } 
	       closedir($dh);
		}			
	}	
	
	/**
	 * Генерация превьюшки
	 * @param $video - ffmpeg_movie
	 * $preview_dir - директория для превьюхи
	 * $preview_name - имя файла превюхи
	 * $preview_name - дополнительный ключ который дописывается в конец имени файла превьюхи
	 */
	private function _get_video_preview($video, $preview_dir, $preview_name, $order = FALSE)
	{
		if ($order !== FALSE) {
			$frame = round(($video->getFrameCount() / 10) * (int)$order);
			// file_name.png > file_name_order.png
			$preview_name = substr($preview_name, 0, strlen($preview_name) - 4)."_".(int)$order.".png";
			
		} else {
			$frame = round($video->getFrameCount() / 10);
		}
		
		if ($frame == 0) $frame = 1;
		$vPreview = $video->getFrame($frame);
		
		// Если невозможно взять кадр, то делаем 1х1 картинку
		$source_image = ($vPreview !== FALSE) ? $vPreview->toGDImage() : imagecreatetruecolor(1, 1);
		$image = imagecreatetruecolor('500', '400');
		imagecopyresampled($image,
			$source_image,
			0, 0, 0, 0,
			640,
			480,
			imagesx($source_image),
			imagesy($source_image)
		);
		imagepng($image, $preview_dir . $preview_name);
		imagedestroy($source_image);
		imagedestroy($image);
	}
	
	/**
	 * Получение списка фото материала
	 * @param $id
	 */
	function action_getphotos($id=null) 
	{
		print Model::factory('pages')->get_photos($id);
    }
	
	/**
	 * Получение экстра-параметров
	 *
	 */
	function action_getoptions($page_id)
	{
		$page_id = intval($page_id);
		$this->Options->get_options('pages', $page_id);
	}

	/**
	 * Получение списка похожих материалов по тегам для одиночного блока
	 *
	 */
	function action_getsimilarbytags($page_id)
	{
		print Model::factory('pages')->get_similar_by_tags($page_id);
	}

	/**
	 * Получение списка похожих материалов по тегам для нижнего множ. блока
	 *
	 */
	function action_getsimilarsbytags($page_id, $single_id)
	{
		print Model::factory('pages')->get_similars_by_tags($page_id, $single_id);
	}

}
