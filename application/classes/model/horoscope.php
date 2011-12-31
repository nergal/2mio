<?php

class Model_Horoscope {

	public function get_last()
	{
		$data = $this->_parse();
		$names = $this->_horo_names();

		$result = array();
		if (count($data) == count($names)) {
			$result = array_combine($names, $data);
		}
		return $result;
	}
	
	private function _horo_names()
	{
		return array('aries', 'taurus', 'gemini', 'cancer', 'leo', 'virgo', 'libra', 'scorpio', 'sagittarius', 'capricornus', 'aquarius', 'pisces');
	}

	/**
	 * Парсилка страницы гороскопа
	 *
	 * @desc   Kак пример использования более
	 * @desc   выокоуровнивых функций, как фреймворка,
	 * @desc   так и php в целом
	 * 
	 * @author nergal
	 *
	 * @uses   Cache, Request, DOMDocument, SimpleXML
	 * @throws Kohana_Exception
	 *
	 * @return array
	 */
	private function _parse()
	{
		if ( ! class_exists('DOMDocument')) {
		    return array();
		}
	
		// Ключ для записи в кэшe
		$cache_key = 'horo';

		$cache = Cache::instance('memcache');

		// Пробуем вытащить данные из кэша
		if ( ! ($items = $cache->get($cache_key))) {
			// Внутренний запрос к сайту, забираем HTML
			$html = Request::factory('http://redday.ru/astrolog/')->execute();

			// Сначала формируем DOM c отключенными ошибками (хак, чтобы парсить не-валидный HTML)
			$dom = new DOMDocument('1.0', 'windows-1251');
			libxml_use_internal_errors(true);
			$dom->loadHTML($html);

			// Возвращаем ошибки на место
			libxml_clear_errors();
			libxml_use_internal_errors(false);

			// Через SimpleXML выбираем xpath'ом нужную часть странцы
			$xml = simplexml_import_dom($dom);
			// Абзацы с третьей позиции - пропускаем вступление
			$xml = $xml->xpath('//td[@class="maintext"]/p[position()>2]');

			// Далее - наполнение массива c данными, сам парсинг
			$name = $content = NULL;
			$items = array();

			foreach ($xml as $key => $item) {
				if ($name !== NULL AND $content !== NULL) {
					$items[] = array(
						'title' => $name,
						'text'  => $content,
					);
					$name = $content = NULL;
				}

				// Узел с тегом <a> - заголовок
				if ($item->a) {
					$name = $item->b;

				// Узел без <br> и без <a> - текст
				} elseif ( ! $item->br) {
					$content = $item;
				}
			}

			// Нормализация полученных данных
			$items = array_map(function($item) {
				// Разбиваем строку заголовка на заголовок и дату
				$date = preg_match('/^(?P<title>[^ ]+) \((?P<start>.+?) - (?P<end>.+)\)$/ui', $item['title'], $matches);
				if ($date) {
					$item['date_start'] = $matches['start'];
					$item['date_end']   = $matches['end'];
					$item['title']      = $matches['title'];
				} else {
					throw new Kohana_Exception('Not valid parsed data');
				}

				// Приводим к нормальной кодировке
				$item = array_map(function($text) {
					$text = preg_replace_callback('#(?P<code>\\\x(?P<hex>[A-F0-9]{2}))#u', function($matches) {
						$text = mb_convert_encoding('&#'.hexdec($matches['hex']).';', 'UTF-8', 'HTML-ENTITIES');
						return $text;
					}, $text);

					// $text = mb_convert_encoding($text, 'ISO-8859-1', 'UTF-8');
					// $text = mb_convert_encoding($text, 'UTF-8', 'Windows-1251');

					return trim($text);
				}, $item);

				return $item;
			}, $items);

			// Сохраняем запись в кэше
			$cache->set($cache_key, $items);
		}

		return $items;
	}
	
}
