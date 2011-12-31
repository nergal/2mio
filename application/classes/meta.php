<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @author nergal
 */
class Meta extends Kohana_Meta
{
	/**
	 * Выборка меты
	 *
	 * @static
	 * @param string $mask
	 * @param array $params
	 * @param boolean $colums == TRUE выбирается meta+title
	 * @param boolean $colums == FALSE выбирается h1
	 * @param boolean $colums == NULL выбирается всё
	 * @return array
	 */
	public static function get($mask, Array $params = array(), $colums = NULL)
	{
		if (($data = self::$_data_cache) == NULL) {
			$data = ORM::factory('meta');

			if (isset($params['section']) AND $params['section'] instanceof Model_Abstract_Section) {
			    $data->where('page.section_id', '=', $params['section']->id);
			} elseif (isset($params['page']) AND $params['page'] instanceof Model_Abstract_Page) {
				$data->or_where('page.page_id', '=', $params['page']->id);
				$data->or_where('page.name', '=', $mask);
			} else {
				$data->where('page.name', '=', $mask);
			}
			
			$data = $data->find_all();
				
			self::$_data_cache = $data;
		}


		$meta = array();
		foreach ($data as $item) {
			$html = $item->type->scheme;
			$html = str_replace('{%value%}', $item->data, $html);

			$meta[$item->type->tag] = $html;
		}

		foreach ($params as $key => $item) {
			unset($params[$key]);
			$key = '{%'.$key.'%}';
			$params[$key] = strip_tags($item);
		};

		foreach ($meta as & $value) {
			$value = strtr($value, $params);
		}

		if ($colums === TRUE) {
			if (isset($meta['h1'])) {
				unset($meta['h1']);
			}
		} elseif ($colums === FALSE) {
			$header = NULL;

			if (isset($meta['h1'])) {
				$header = $meta['h1'];
			}

			return $header;
		}

		self::$_meta = $meta;
		return $meta;
	}
}
