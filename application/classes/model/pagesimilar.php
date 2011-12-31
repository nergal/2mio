<?php

/**
 * @author nergal
 * @package btlady
 */
class Model_Pagesimilar extends ORM
{
    /**
     * Имя таблицы
     * @var string
     */
    protected $_table_name = 'pages_similar';
    
    protected $_belongs_to = array(
        'article' => array(
            'model' => 'article',
            'foreign_key' => 'similar_id',
        ),
    );

}