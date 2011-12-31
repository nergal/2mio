<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Cron extends Controller
{
	public function before()
	{
		if ( ! Kohana::$is_cli) {
			throw new HTTP_Exception_404('Not found');
		}
	}

	/**
	 * Обновление счётчиков из кэша в БД
	 *
	 * @uses Cache
	 * @uses DB
	 *
	 * @throws Kohana_Exception
	 */
	public function action_counters()
	{
		ob_get_clean();

		// Генерируем и проверяем файловый лок
		$_lock_file = '/tmp/kohana.lock';

		if (file_exists($_lock_file)) return;
		file_put_contents($_lock_file, date('r'));

		$cache = Cache::instance('memcache');
		
		$memcache = $cache->get_raw_instance();

		$items = array();
		$allSlabs = $memcache->getExtendedStats('slabs');

		foreach($allSlabs as $server => $slabs) {
		    foreach($slabs AS $slabId => $slabMeta) {
                        if (is_numeric($slabId)) {
			    $cdump = $memcache->getExtendedStats('cachedump', (int) $slabId);
			    foreach($cdump AS $server => $entries) {
				if($entries) {
				    foreach($entries AS $eName => $eData) {
					if (preg_match('/^count_views/', $eName)) {
					    $items[$eName] = $memcache->get($eName);
					}
				    }
				}
			    }
			}
		    }
		}
		
		
		// Получение всех ключей со счетчиками
		// $items = $cache->find('counts');
		$list = array();

		try {
			foreach ($items as $key => $value) {
				// валидация имени ключа
				$key = explode('_', $key);
				if (count($key) == 4) {
					list($action, $type, $id, $date) = $key;

					// выбираем только ключи счетчиков просмотров
					if ($action == 'count' AND $type == 'views') {
						$id = intVal($id);
						$date = intVal($date);

						$key = implode('_', array($action, $type, $id, $date));
						$date = date('Y-m-d', strtotime($date));

						$value = intVal($value);

						// Формируем массив по дате и id с кол-вом просмотров в ключах
						if ( ! array_key_exists($date, $list)) {
							$list[$date] = array();
						}

						$list[$date][$id] = $value;
					}
				} else {
					throw new Kohana_Exception('Неверно сформированный ключ кэша для просмотра, "'.$key.'"');
				}
			}
			
			// Запрос для выборки текущего значения за указаную дату
			$sql_old_value = DB::query(
				Database::SELECT,
				'SELECT
					`count`,
					`page_id` AS `id`
				 FROM `page_visits`
				 WHERE
				 	`page_id` IN :page_id
				 	AND `date` = :date'
			);

			// Запрос для выборки суммы просмотров для вычета разницы
			$sql_get_total = DB::query(
				Database::SELECT,
				'SELECT
					`views_count` AS `count`,
					`id`
				 FROM `pages`
				 	WHERE `id` IN :page_id'
			);

			// Максимально допустимое значение просмотров
			$max = round(pow(2, 31) / 10);

			// Формирование удобного для работы массива ключ/значение
			$sql2array = function(Database_Query $query, $params) {
				$data = $query->parameters($params)->execute();

				$values = array();
				foreach ($data->as_array() as $item) {
					$values[$item['id']] = $item['count'];
				}

				return $values;
			};

			// Формиование массива, готового к запросу вставки
			array_walk($list, function( & $items, $key, $sql2array)
								use ($sql_old_value, $sql_get_total, $max, $cache) {
				// Параметры для выбора текущих данных
				$sql_params = array(
					':page_id' => array_keys($items),
					':date' => $key,
				);

				return array_walk($items, function( & $value, $key, $data)
											use ($cache) {
					list($values, $actual, $date, $max) = $data;

					// Нормализация значения ключа:
					// новое_за_сегодня = ((текущая_сумма_из_кэша - текущая_сумма_из_статьи) + текущее_за_сегодня)
					$value-= $actual[$key];
					$value+= (array_key_exists($key, $values) ? $values[$key] : 0);

					// Формирование ключа кэша
					$cache_key = array('count_views', $key, str_replace('-', '', $date));
					$cache_key = implode('_', $cache_key);

					// Удаление обработанной записи
					$cache->delete($cache_key);

					// Определение выхода за пределы допустимых значений
					if ($value < 0 OR $value > $max) {
						$message = 'Untyptical value "'.$value.'" in key "'.$cache_key.'"';
						Kohana::$log->add(Log::CRITICAL, $message);

						// Удаление поломанного ключа
						$value = NULL;
					}
				}, array(
					$sql2array($sql_old_value, $sql_params),
					$sql2array($sql_get_total, $sql_params),
					$key, $max,
				));
			}, $sql2array);

			// Фильтрация массива на предмет удаленных значений
			$list = array_map('array_filter', $list);
			$list = array_filter($list);

			// Подготовка ко множественной встаке
			{
				$id_list = array();
				$insert = DB::replace('page_visits')->columns(array('count', 'page_id', 'date'));
				foreach ($list as $date => $items) {
					foreach ($items as $id => $value) {
						// Вставка значения в БД
						$values = array($value, $id, $date);
						$insert->values($values);

						// Сохранение id для обновления pages
						$id_list[] = $id;
					}
				}

				// Запрос вставки в БД
				$insert = $insert->execute();
			}
		} catch (Kohana_Exception $e) {
			Kohana::$log->add(Log::ERROR, $e->getMessage());
		}

		// Обновление значений в pages
		$sql = 'UPDATE
			    	`pages` `p1`,
				    (
						SELECT
						    SUM(`count`) `counts`,
						    `page_id`
						FROM `page_visits`
						WHERE
						    `page_id` IN :page_id
						GROUP BY `page_id`
				    ) `p2`
				SET `p1`.`views_count` = `p2`.`counts`
				WHERE `p2`.`page_id` = `p1`.`id` AND `p2`.`counts` < 20000000';

		if (isset($insert) AND $insert AND ! empty($id_list)) {
		    $query = DB::query(Database::UPDATE, $sql);
		    $query->parameters(array(':page_id' => $id_list));

		    try {
			if ($query->execute()) {
				$cache->delete('counts');
			} else {
				Kohana::$log->add(Log::ERROR, 'Cache views count reaclculating failed')->write();
			}
		    } catch (Database_Exception $e) {
			Kohana::$log->add(Log::ERROR, $e->getMessage())->write();
		    }
		}

		// Снятие лока
		$cache->delete('counts');
		unlink($_lock_file);
	}

	/**
	 * Обработчик очереди сообщений
	 *
	 * @param string $name
	 */
	public function action_queue($name = 'default')
	{
		try {
			$queue = Queue::instance();

			$callback = array($this, 'queue_'.$name);

			$count = NULL;
			if (is_callable($callback)) {
			    try {
				$count = $queue->proceed($name, $callback);
			    } catch (Exception $e) {
				Kohana::$log->add(Log::ERROR, $e->getMessage());
			    }
			}

			if ($count === NULL) {
				Kohana::$log->add(Log::CRITICAL, 'Не найден обработчик для события "'.$name.'"');
			} elseif ($count > 0) {
				Kohana::$log->add(Log::INFO, 'Успешно обработано '.intVal($count).' сообщений');
			}
		} catch (Kohana_Exception $e) {
			Kohana::$log->add(Log::ERROR, $e->getMessage());
		}
	}

	public function queue_messages($job_id, $body)
	{
		return Mailer::factory('user')->send_subscribe($body);
	}

	public function queue_confirmation($job_id, $body)
	{
		return Mailer::factory('user')->send_confirm($body);
	}

	public function queue_reset($job_id, $body)
	{
		return Mailer::factory('user')->send_reset($body);
	}
	
	public function queue_welcome($job_id, $body)
	{
		return Mailer::factory('user')->send_welcome($body);
	}

        public function action_topnews()
        {
            $subscribes = ORM::factory('subscription')->find_all();

            foreach ($subscribes as $subscribe) {
                Queue::instance()->add('topnews', $subscribe->id);
            }
        }

        public function queue_topnews($job_id, $id)
	{
		return Mailer::factory('user')->send_topnews($id);
	}
}
