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

}