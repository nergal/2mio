<?php

class Model_Photo extends Model_Abstract_Page {
	/**
	 * Алиас для выборки типа из type_pages
	 * @var string
	 */
	protected $_type_alias = 'photo';

	/**
	 * Внешние связи "один ко многим"
	 * @var array
	 */
	protected $_belongs_to = array(
		'section' => array(
		    'model' => 'gallery',
		    'foreign_key' => 'section_id',
		),
		'type' => array(
		    'model' => 'pagetype',
		    'foreign_key' => 'type_id',
		),
		'user' => array(
		    'model' => 'user',
		    'foreign_key' => 'user_id',
		),
	);

	public function get_neighbors($id = NULL)
	{
		if ($id === NULL AND ! $this->loaded()) {
			$this->where('id', '=', $id)->find();
		}

		if ($this->loaded()) {
			$sql = "(
				SELECT
					`p`.`id` `down`, NULL `up`
				FROM `pages` `p`
					LEFT JOIN `type_pages` `tp` ON `tp`.`id` = `p`.`type_id`
				WHERE
					`p`.`id` < :id
					AND `p`.`section_id` = :section_id
					AND `tp`.`alias` = 'photo'
					AND `p`.`showhide` = 1
				ORDER BY `p`.`id` DESC
				LIMIT 1
			) UNION (
				SELECT
					NULL, `p`.`id`
				FROM `pages` `p`
					LEFT JOIN `type_pages` `tp` ON `tp`.`id` = `p`.`type_id`
				WHERE
					`p`.`id` > :id
					AND `p`.`section_id` = :section_id
					AND `tp`.`alias` = 'photo'
					AND `p`.`showhide` = 1
				ORDER BY `p`.`id` ASC
				LIMIT 1
			)";

			$params = array(
				':id' => $this->id,
				':section_id' => $this->section_id,
			);

			$query = DB::query(Database::SELECT, $sql);
			$query = $query->parameters($params);

			$up = NULL;
			$down = NULL;
			foreach ($query->execute() as $item) {
				if ($item['up'] !== NULL) {
					$up = $item['up'];
				} elseif ($item['down'] !== NULL) {
					$down = $item['down'];
				}
			}

			$result = array_map(function($item)
			{
				return ORM::factory('photo', $item);
			}, array($down, $up));

			return $result;
		} else {
			throw new Kohana_Exception('Incorrect usage of Model_Photo::get_neighbors()');
		}
	}
}