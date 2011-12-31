<?php

class Model_Media extends ORM {
    protected $_table_name = 'pages_media';

    protected $_belongs_to = array(
		'page' => array(
		    'model' => 'page',
		    'foreign_key' => 'page_id',
		),
    );
}
