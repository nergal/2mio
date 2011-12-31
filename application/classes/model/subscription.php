<?php

/**
 * Модель подписок на топ материалов
 *
 * @author nergal
 * @package btlady
 */
class Model_Subscription extends ORM
{
    /**
     * Имя таблицы
     * @var string
     */
    protected $_table_name = 'user_subscription';

    /**
     * Фильтр вводимых значений
     *
     * @return array
     */
    public function filters()
    {
        return array(
            'sections' => array(
                array(function($data) {
                    if (is_array($data)) {
                        return implode(',', $data);
                    }

                    return $data;
                }),
            ),
        );
    }

    /**
     * Описание структуры таблицы
     *
     * @return array
     */
    public function rules()
    {
        return array(
            'email' => array(
                array('email'),
                array('min_length', array(':value', 2)),
                array('max_length', array(':value', 3000)),
            ),
            'username' => array(
                array('not_empty'),
                array('min_length', array(':value', 2)),
                array('max_length', array(':value', 255)),
            ),
            'sections' => array(
                array('regex', array(':value', '/^[ ,\d]*$/')),
            ),
            'hash' => array(
                array('regex', array(':value', '/^[0-9a-f]{32}$/')),
            ),
        );
    }
}
