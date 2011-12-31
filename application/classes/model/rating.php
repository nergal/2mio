<?php

/**
 * Модель рейтингов
 *
 * @author nergal
 * @package btlady
 */
class Model_Rating extends ORM
{
	/**
	 * Имя таблицы
	 * @var string
	 */
    protected $_table_name = 'pages_ratings';

    protected $_belongs_to = array(
    	'page' => array(
    		'model' => 'page',
			'foreign_key' => 'page_id',
    	),
    	'user' => array(
    		'model' => 'user',
    		'foreign_key' => 'user_id',
    	),
    );

    /**
     * Выборка рейтинга
     *
     * @param  Model_Abstract_Page $page модель материала
     * @param  string              $hash IP пользователя
     *
     * @return object(value, is_voted) - оценка и разрешение голосования
     */
    public function get_rating(Model_Abstract_Page $page, $ip)
    {
    	$query = DB::query(Database::SELECT,
	    	'SELECT
				AVG(`r`.`value`) `value`,
				SUM(`r`.`value`) `sum`,
				COUNT(`r`.`hash`) `counts`,
				IF(`r2`.`id` IS NULL, 0, 1) `is_voted`
			FROM `pages_ratings` `r`
			LEFT JOIN `pages_ratings` `r2` ON
				`r2`.`ip` = :ip
				AND `r2`.`date` = CURRENT_DATE()
				AND `r2`.`page_id` = `r`.`page_id`
			WHERE `r`.`page_id` = :page_id');

    	$query->parameters(array(
    		':ip' => $ip,
    		':page_id' => $page->id,
    	));

		$result = $query
			->as_object()
			->execute();

		return $result->current();
    }

	/**
     * Описание структуры таблицы
     *
     * @return array
     */
	public function rules()
	{
        return array(
            'value' => array(
                array('not_empty'),
                array('min_length', array(':value', 1)),
                array('max_length', array(':value', 1)),
            ),
            'hash' => array(
            	array('not_empty'),
            ),
            'date' => array(
            	array('not_empty'),
            	array('regex', array(':value', '/^[\d]{4}\-[\d]{2}\-[\d]{2}$/')),
            ),
            'ip' => array(
                array('not_empty'),
                array('regex', array(':value', '/^[\d]+$/')),
            ),
		);
    }
}
