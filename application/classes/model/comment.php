<?php

/**
 * Модель коментариев
 *
 * @author nergal
 * @package btlady
 */
class Model_Comment extends ORM
{
	/**
	 * Имя таблицы
	 * @var string
	 */
    protected $_table_name = 'comments';

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

    public function fetch($id, $page = FALSE, $per_page = 10)
    {
        $this->order_by('date');
        $this->reset(FALSE);

        $count = $this->count_all();

        if ($page !== FALSE) {
            $offset = abs(--$page * $per_page);
            $this
                ->offset($offset)
                ->limit($per_page);
        } else {
            $this->limit(999); // Ограничение, если оооочень много комментариев
        }
        $query = $this->find_all();
        
        return array($count, $query);
    }

	/**
     * Описание структуры таблицы
     *
     * @return array
     */
	public function rules()
	{
        return array(
            'body' => array(
                array('not_empty'),
                array('min_length', array(':value', 4)),
                array('max_length', array(':value', 3000)),
            ),
/*            'author' => array(
                array('min_length', array(':value', 2)),
                array('max_length', array(':value', 255)),
            ),
            'email' => array(
                array('not_empty'),
                array('email'),
            ),
*/
            'ip' => array(
                array('not_empty'),
                array('regex', array(':value', '/^[\d]+$/')),
            ),
		);
    }
}
