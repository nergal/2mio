<?php
/**
 * Подгонка изображений превью
 *
 * @author sokol, 2011
 */
 
class Thumbnail
{
	private $image_sizes = array();
	private $resize_types = array();
	private $host;
	private $src_folders = array('images', 'uploads');
	private $dst_folder = 'thumbnails';
	private $new_folder_mode = 0755;
	
	const CROP_WIDTH = 'crop_width';
	const CROP_HEIGHT = 'crop_height';
	const CROP_LEFT = 'crop_left';
	const CROP_TOP = 'crop_top';
	const IMAGE_WIDTH = 'image_width';
	const IMAGE_HEIGHT = 'image_height';
	const IMAGE_LEFT = 'image_left';
	const IMAGE_TOP = 'image_top';
	const CROP_TYPE = 'crop_type';
	const IMAGE_URL = 'image_url';
	
	const CROPR = 'cropr';
	const CROPG = 'cropg';
	const CROP = 'crop';
	const RES = 'res';
	
	public function __construct()
	{
		$this->init_vars();
		$this->init_image_sizes();
		$this->init_resize_types();
	}
	
	private function init_vars()
	{
		$this->host = $_SERVER['HTTP_HOST'];	
	}
	
	private function init_image_sizes()
	{
		$config = Kohana::config('resizer');

		foreach ($config->get('allowed_sizes') as $size) 
		{
			if (!is_array($size)) 
				$size = explode('x', $size);
				
			$this->image_sizes[] = $size;
		}
		
		sort($this->image_sizes);
	}
	
	private function init_resize_types()
	{
		$this->resize_types = array(
			'Изм. размер и обрезать (cropr)' => self::CROPR,
			'Изм. размер и обрезать (cropg)' => self::CROPG,
			'Обрезать (crop)' => self::CROP, 
			'Изм. размер (res)' => self::RES
		);
	}
	
	public function get_image_sizes()
	{
		return $this->image_sizes;	
	}
	
	public function get_resize_types()
	{
		return $this->resize_types;	
	}
	
	/**
	 * Создать превью
	 * 
	 * @param Request $request
	 * @return array
	 */
	public function create_thumbnail($request)
	{
		try {
			$params = $this->get_post_params($request);
			$src_path = $this->get_image_path_from_url($params[self::IMAGE_URL]);
			$image = Image::factory($src_path, 'gd');
			$crop_width = $params[self::CROP_WIDTH];
			$crop_height = $params[self::CROP_HEIGHT];
			$crop_top = $params[self::CROP_TOP] - $params[self::IMAGE_TOP];
			$crop_left = $params[self::CROP_LEFT] - $params[self::IMAGE_LEFT];
			
			$image->resize($params[self::IMAGE_WIDTH], $params[self::IMAGE_HEIGHT], Image::INVERSE);
			
			switch($params[self::CROP_TYPE])
			{
				case self::CROPR:
				case self::CROPG:
				case self::CROP:
				case self::RES:
					$image->crop($crop_width, $crop_height, $crop_left, $crop_top);
					$thumbnail_path = $this->get_thumbnail_path($src_path, $params[self::CROP_TYPE], $crop_width, $crop_height);
					$this->save_image($image, $thumbnail_path);
					
			}
		} catch(Kohana_Exception $e) {
			return array('result' => false, 'msg' => 'Ошибка: ' . $e->getMessage());
		}
		
		return array('result' => true, 'msg' => 'Превью успешно сохранено');
	}
	
	private function get_thumbnail_path($src_path, $crop_type, $img_width, $img_height)
	{
		$path = str_replace($this->src_folders, $this->dst_folder, $src_path);
		$path = explode(DIRECTORY_SEPARATOR, $path);
		
		$i = 0;
		while(array_shift($path) != $this->dst_folder)
			if($i++ > 100)
				break;
		
		$path = array_merge(array($this->dst_folder), $path);
		$img_name = array_pop($path);
		array_push($path, $crop_type . '_' . $img_width . 'x' . $img_height);
		array_push($path, $img_name);
		$path = DOCROOT . implode(DIRECTORY_SEPARATOR,	$path);

		return $path;
	}
	
	/**
	 * Сохранить превью
	 * 
	 * @param Image $image Объект Image
	 * @param string $path Путь сохранения
	 * @return void
	 * @throws  Kohana_Exception
	 */
	private function save_image($image, $path)
	{
		$array_path = explode(DIRECTORY_SEPARATOR, $path);
		array_pop($array_path);
		$folder = implode(DIRECTORY_SEPARATOR, $array_path);
		
		if(!file_exists($folder))
		{
			if(!mkdir($folder, $this->new_folder_mode, true))
				throw new Kohana_Exception("Не удалось создать папку для сохранения превью");
		}

		if(!$image->save($path))
			throw new Kohana_Exception("Не удалось сохранить превью");
	}
	
	/**
	 * Полный путь к исходному изображению
	 * 
	 * @param string $url 
	 * @return string
	 * @throws  Kohana_Exception
	 */
	private function get_image_path_from_url($url)
	{
		$url = str_replace('http://', '', $url);
		$url = str_replace($this->host, '', $url);
		if(strpos($url, '/') === 0)
			$url = substr($url, 1);
			
		$path = realpath(DOCROOT . $url);
		if(!$path)
			throw new Kohana_Exception("Изображение '{$url}' не найдено на сервере");
		
		return $path;
	}
	
	/**
	 * Получить данные пост-запроса
	 * 
	 * @param object $request
	 * @return array
	 * @throws  Kohana_Exception
	 */
	private function get_post_params($request)
	{
		$params = array(
			self::CROP_WIDTH => $request->post('crop_width'),
			self::CROP_HEIGHT => $request->post('crop_height'),
			self::CROP_LEFT => $request->post('crop_left'),
			self::CROP_TOP => $request->post('crop_top'),
			self::IMAGE_WIDTH => $request->post('image_width'),
			self::IMAGE_HEIGHT => $request->post('image_height'),
			self::IMAGE_LEFT => $request->post('image_left'),
			self::IMAGE_TOP => $request->post('image_top'),
			self::CROP_TYPE => $request->post('crop_type'),
			self::IMAGE_URL => $request->post('image_url')
		);
		
		if(!in_array(array($params[self::CROP_WIDTH], $params[self::CROP_HEIGHT]), $this->image_sizes))
			throw new Kohana_Exception('Неразрешенный размер превью ' . implode('x', array($params[self::CROP_WIDTH], $params[self::CROP_HEIGHT])));
			
		if(!in_array($params[self::CROP_TYPE], $this->resize_types))
			throw new Kohana_Exception('Незвестная операция ' . $params[self::CROP_TYPE]);
			
		if($params[self::IMAGE_WIDTH] <= 0 || $params[self::IMAGE_HEIGHT] <= 0)
			throw new Kohana_Exception('Неправильный размер изображения ' . $params[self::IMAGE_WIDTH] . 'x' . $params[self::IMAGE_HEIGHT]);
			
		if(!$params[self::IMAGE_URL])
			throw new Kohana_Exception('Url c исходным изображением не задан');
		
		return $params;
	}
}
