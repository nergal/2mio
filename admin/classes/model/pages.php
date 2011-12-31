<?php

/**
 * Модель материалов
 *
 * @author kolex, 2011
 * @package btlady-admin
 */
class Model_Pages extends Model_Common {
	
	public static $sid = 0;
	public static $page_id = 0;
	public static $self;
	
	public function __construct() {
		parent::__construct();
		if(!class_exists('GridConnector'))
		{
			require($this->pathDhtmlx."grid_connector.php");
		}
		
		self::$self = $this;
	}
	
	/**
	 * Получение древовидного списка всех разделов материалов сайта
	 */
	public function get_sections($request) {
		require ($this->pathDhtmlx . 'treegrid_connector.php');
		
		$grid = new TreeGridConnector($this->connection);
		$grid->enable_log($this->connectorLog, true);
		$grid->dynamic_loading(true);
		
		function color_rows($item) {
			$sid = $item->get_value('id');
			$show = (int) $item->get_value('showhide');
			if ($show == 0)
			{
				$color = '#FFDDDD';  //помечаем скрытые разделы другим цветом
			} else {
				if ($item->get_index() % 2)
					$color = '#E7EDEF';
				else
					$color = '#FFFFFF';
			}
			
			$item->set_row_color ( $color );
			$item->set_value('showhide', $show);
			$item->set_value('`order`', intval($item->get_value('order')));
		}
		$grid->event->attach('beforeRender', 'color_rows');
		
		$pid = $this->extractIdFromUrl($request->uri());
		
		//добавление сортировки
		function custom_sort($sorted_by) {
			if (!sizeof($sorted_by->rules)) {
				$sorted_by->add('`order`', 'ASC');
				$sorted_by->add('`name`', 'ASC');
			}
		}
		$grid->event->attach('beforeSort', 'custom_sort');
		
		//добавление фильтра
		function custom_filter($filter_by) {
			if (!count( $filter_by->rules ) || strpos ( $filter_by->rules [0], 'LIKE ' ) === false) {
				//$filter_by->add('name', '=%=', 'NOT LIKE');
			}
		}
		
		function beforeInsert($action)
		{
			if($action->get_value('parent_id') == 0)
			{
				$sql = "INSERT INTO sections(name, name_url,`show`,`order`,parent_id) VALUES (:name, :name_url, :show, :order, NULL)";
				$query = DB::query(Database::INSERT, $sql);
				$query->parameters(array(
					':name' => $action->get_value('name'),
					':name_url' => $action->get_value('name_url'),
					':show' => $action->get_value('`show`'),
					':order' => $action->get_value('`order`')
				));
				if($query->execute() > 0)
					$action->success();
				else
					$action->error();
			}
		}
		
		$grid->event->attach('beforeInsert', 'beforeInsert'); 
		$grid->event->attach('beforeFilter', 'custom_filter' );
		$grid->render_table ('sections', 'id', 'name,name_url,showhide,`order`,type_id,votes_active', '', 'parent_id');
	}
	
	public function get_pages($sid = null)
	{
		$grid = new GridConnector($this->connection);
		$grid->enable_log($this->connectorLog,true);
		$grid->dynamic_loading(100); 
		
		//расцветка записей в гриде
		function color_rows($item) {
			$fixed = (int)$item->get_value('fix');
			$showhide = (int)$item->get_value('showhide');
			
            if ($item->get_index()%2)
				$color = '#E7EDEF';
			else
				$color = '#FFFFFF';
				
            if($fixed == 1)
            {
                $color = '#87EBEB'; // colorize fixed rows
            } 
			if($showhide == 0)
            {
                $color = '#ECD4D4'; // colorize hidden rows
            }                                                                                                                                                              
				
			$item->set_row_color($color);
		}
		$grid->event->attach('beforeRender','color_rows');
		
		self::$sid = $sid; //делаем ИД видимым из функции
		function filter_custom($filter_by)
		{
			if ((!count($filter_by->rules) || strpos($filter_by->rules[0],'LIKE ')===false) && (int)Model_Pages::$sid > 0)
			{
				$filter_by->add('section_id', Model_Pages::$sid, 'LIKE');
			}
		}
		$grid->event->attach('beforeFilter', 'filter_custom');

		function custom_sort($sorted_by){
			if (!sizeof($sorted_by->rules)){
				$sorted_by->add('date', 'DESC');
			}
		}
		$grid->event->attach("beforeSort","custom_sort");
		
        function clear_cache()
        {
        	Model_Common::$cache->delete_tag('pages');
        }
        $grid->event->attach('afterProcessing', 'clear_cache');
        
		$grid->render_table('pages','id', 'title,date,photo,section_id,name_url,showhide,fix,type_id,announcing,yandex_rss,informer_photo,informer_video,recommended,partners_rss');
		
	}

	/**
	 * Получаем содержимое материала
	 * @param (int) $id
	 */
	public function get_content($id)
	{
		$content = null;
 
		if(!empty($id))
		{
			$sql = "
			SELECT
				p.description, 
				p.body,
				s.name_url section, 
				s.id section_id 
			  FROM 
				pages p
				LEFT JOIN sections s ON s.id = p.section_id 
			 WHERE 
			 	p.id = ".$id." 
			 LIMIT 1";
			
			$result = $this->db->query(Database::SELECT, $sql);
			
			if(count($result) > 0)
			{
				$rows = $result->as_array();
				//заворачиваем в json   
				$content = json_encode(array(
					'descr' => $rows[0]['description'],
					'body'  => $rows[0]['body'],
					'url'   => '<a href="http://' . Controller_Pages::$domain. '/' . $rows[0]['section'].'" target="_blank">Просмотреть &gt;&gt;</a>'
				));
			}
		}
		return $content;
	}	

	/**
	 * Записываем содержимое материала
	 * @param (int) $id
	 */
	public function set_content($data)
	{
		$sql = "
			UPDATE pages
			  SET 
				description = '" . addslashes($data['descr']) . "', 
				body  = '" . addslashes($data['body']) . "' 
			 WHERE id = " . $data['id'];
		
		$result = $this->db->query(Database::UPDATE, $sql);
	}
	
	/**
	 * Получение списка всех тэгов по материалам сайта
	 */
	public function get_alltags()
	{
		$grid = new GridConnector($this->connection);
		$grid->enable_log($this->connectorLog, true);
		$grid->dynamic_loading(20);

		function color_rows($item)
		{
			$sid = $item->get_value('id');
			if ($item->get_index()%2)
				$color = '#E7EDEF';
			else
				$color = '#FFFFFF';

			$item->set_row_color($color);
		}
		$grid->event->attach('beforeRender', 'color_rows');
		
		function custom_sort($sorted_by){
			if (!sizeof($sorted_by->rules)){
				$sorted_by->add('`order`', 'ASC');
				$sorted_by->add('`name`',  'ASC');
			}
		}
		$grid->event->attach('beforeSort', 'custom_sort');

		$grid->sql->set_transaction_mode('record');
		
		$grid->render_table('tags', 'id', 'name');
	}
	
   /**                                                                                                                                                                                              
     * Получаем список тегов по материалу                                                                                                                                                               
     */                                                                                                                                                                                              
    public function get_pagetags($id = 0)                                                                                                                                                             
    {                                                                                                                                                                                                
        $grid = new GridConnector($this->connection);                                                                                                                                                
        $grid->enable_log($this->connectorLog, true);                                                                                                                                        
        $grid->dynamic_loading(20);                                                                                                                                                          
                                                                                                                                                                                                     
        function color_rows($item)                                                                                                                                                                   
        {                                                                                                                                                                                            
            $sid = $item->get_value('tag_id');                                                                                                                                                   
            if ($item->get_index()%2)                                                                                                                                                            
                $color = '#E7EDEF';                                                                                                                                                                  
            else                                                                                                                                                                                     
                $color = '#FFFFFF';                                                                                                                                                                  
                                                                                                                                                                                                     
            $item->set_row_color($color);                                                                                                                                                            
        }                                                                                                                                                                                            
        $grid->event->attach('beforeRender','color_rows');                                                                                                                                   
                                                                                                                                                                                                     
        if(count($_POST))                                                                                                                    
        {                                                                                                                                    
            foreach($_POST as $key=>$value)                                                                                                  
            {                                                                                                                                
                $parts = explode('_',$key);                                                                                                                                                          
                if(count($parts) > 1)                                                                                                        
                {                                                                                                                            
                    switch(end($parts)) { //[1]                                                                                                                                                      
                        case 'c1' : $c1  = $value; break;                                                                                    
                        case 'c2' : $c2  = $value; break;                                                                                    
                    }                                                                                                                        
                }                                                                                                                         
            }                                                                                                                                                                                        
            $sqlUpd = "
            REPLACE INTO pages_tags (
                page_id,
                tag_id
             )
             VALUES(
               ".$c1.",
               ".$c2."
            )";

            $grid->sql->attach("Update", $sqlUpd);
        }

        $sqlDel = "DELETE FROM pages_tags WHERE id = {id}";

        $grid->sql->attach("Delete", $sqlDel);

        $sql = "
           SELECT
              p.id,
              t.`name` tag,
              p.page_id,
              p.tag_id
            FROM
              tags t,
              pages_tags p
            WHERE
               t.id = p.tag_id
              AND p.page_id = " . $id . "
        ";

        function clear_cache()
        {
        	Model_Common::$cache->delete_tag('pages_tags');
        }
        $grid->event->attach('afterProcessing', 'clear_cache');
        
        $grid->render_sql($sql,'id','tag,page_id,tag_id');
    }

	/**
	 * Получение списка всех тэгов по материалам сайта
	 */
	public function get_comments($id=0)
	{
		$grid = new GridConnector($this->connection);
		$grid->enable_log($this->connectorLog, true);
		$grid->dynamic_loading(20);

		function color_rows($item)
		{
			$sid = $item->get_value('id');
			if ($item->get_index()%2)
				$color = '#E7EDEF';
			else
				$color = '#FFFFFF';

			$item->set_row_color($color);
		}
		$grid->event->attach('beforeRender','color_rows');

		function custom_sort($sorted_by){
			if (!sizeof($sorted_by->rules)){
				$sorted_by->add('date', 'DESC');
			}
		}
		$grid->event->attach("beforeSort","custom_sort");

		self::$page_id = $id;

		function custom_filter($filter_by)
		{   
			if ($filter_by->rules || strpos( $filter_by->rules [0], '=') === false)
			{
				if (Model_Pages::$page_id > 0)
					$filter_by->add('page_id', Model_Pages::$page_id, '=');
			}
		}
		$grid->event->attach('beforeFilter', 'custom_filter');
		$grid->sql->set_transaction_mode('record');

		$sql = "DELETE FROM comments WHERE id = {id}";

		$grid->sql->attach('Delete', $sql);
		
		$sql = "
		SELECT
			c.id,
			c.body,
			c.date,
			c.author,
			p.title,
			c.page_id,
			c.topic,
			c.ip
		  FROM
			comments c,
			pages p
		  WHERE
			  1 = 1
			AND c.page_id = p.id
		";
		
        function clear_cache()
        {
        	Model_Common::$cache->delete_tag('comments');
        }
        $grid->event->attach('afterProcessing', 'clear_cache');
		
		$grid->render_sql($sql,'id','body,date,author,title,page_id,topic,ip');
	}
	
	/**
	 * Чтение фото по-умолчанию для материала
	 * @param $id ID материала
	 */
	function get_defaultphoto($id) 
	{
		$sql = "SELECT photo, type_id FROM pages WHERE id = " . $id;
		$result = $this->db->query(Database::SELECT, $sql);
		if(count($result) > 0)
		{
		  	$rows = $result->as_array();
		   	$photo = $rows[0];
		} else 
			$photo = null;
			
		return $photo;
	}
	
	/**
	 * Загрузка фото по-умолчанию для материала
	 * @param $postid
	 */
	function set_photo($id, $photo) 
	{
		$sql = "UPDATE pages SET photo = '".$photo."' WHERE id = ".$id;
		$query = $this->db->query(Database::UPDATE, $sql);
		Model_Common::$cache->delete_tag('pages');
	}
		
	/**
	 * Удаление фото по-умолчанию для материала
	 * @param $postid
	 */
	function delete_photo($id) 
	{
		$sql = "UPDATE pages SET photo = NULL WHERE id = ".$id;
		$query = $this->db->query(Database::UPDATE, $sql);
		Model_Common::$cache->delete_tag('pages');
	}
	
	/**
	 * Список фотографий выбранного материала
	 * @param (int) $id ИД материала
	 */
	function get_photos($id) 
	{
		$grid = new GridConnector($this->connection);
		$grid->enable_log($this->connectorLog,true);
		$grid->dynamic_loading(20);
		 
		//предварительная обработка записей до рендеринга
		function before_render($item) {
			if ($item->get_index()%2)
				$color = '#E7EDEF';
			else
				$color = '#FFFFFF';
				
			$item->set_row_color($color);
		}
		$grid->event->attach('beforeRender','before_render');
		
		Model_Pages::$page_id = $id;
		
		function custom_filter($filter_by)
		{
			if (!count($filter_by->rules) || strpos($filter_by->rules[0],'LIKE ')===false)
			{
				$filter_by->add('page_id', Model_Pages::$page_id, '=');
			}
		}
		$grid->event->attach('beforeFilter', 'custom_filter');

		//правка фото: делаем относительный путь 
		function before_update($action)																																					
		{
			$url = $action->get_value('name');
			$parts = explode('/', $url);
			unset($parts[0]); unset($parts[1]); unset($parts[2]);
			$name = '/' . implode('/', $parts);
			$action->set_value('name', $name);
		}		
		$grid->event->attach('beforeUpdate', 'before_update');
		
		//добавление сортировки
		function custom_sort($sorted_by) {
			if (!sizeof($sorted_by->rules)) {
				$sorted_by->add('`orders`', 'ASC');
				$sorted_by->add('`date`', 'ASC');
			}
		}
		$grid->event->attach('beforeSort', 'custom_sort');
		
        function clear_cache()
        {
        	Model_Common::$cache->delete_tag('pages_media');
        }
        $grid->event->attach('afterProcessing', 'clear_cache');
        
		$grid->render_table('pages_media', 'id', 'name,defolt,description,page_id,type_id,orders');
	}

	/**
	 * Чтение фото из списка в гриде
	 * @param $id ID фото
	 */
	function get_photo($id) 
	{
		$sql = "SELECT `name` FROM pages_media WHERE id = " . $id;
		$result = $this->db->query(Database::SELECT, $sql);
		if(count($result) > 0)
		{
		  	$rows = $result->as_array();
		   	$photo = $rows[0]['name'];
		} else 
			$photo = null;
			
		return $photo;
	}
	
	function delete_page_media($media_id)
	{
		$sql = "DELETE FROM pages_media WHERE id = :id";
		$query = DB::query(Database::DELETE, $sql)->parameters(array(':id' => $media_id));
		$affected_rows = $query->execute();
		
		if($affected_rows > 0)
			return true;
			
		return false;
	}

	/**
	 * Получение списка похожих материалов по тегам для синглового блока
	 * @param (int) $page_id ИД материала
	 */
	function get_similar_by_tags($page_id)
	{
		$grid = new GridConnector($this->connection);
		$grid->enable_log($this->connectorLog,true);
		$grid->dynamic_loading(20);
		 
		//предварительная обработка записей до рендеринга
		function before_render($item) {
			if ($item->get_index()%2)
				$color = '#E7EDEF';
			else
				$color = '#FFFFFF';
				
			$item->set_row_color($color);
		}
		$grid->event->attach('beforeRender','before_render');

		$page_id = (int)$page_id;
		
		Model_Pages::$page_id = $page_id;

		$sqlUpd = "UPDATE pages_similar SET single = 1 WHERE page_id = {id} AND similar_id = 0 ";  // fake update, stub

        //unset($_POST['ids']); // hack for the dhtmlx

		// разбираем вручную данные dataProcessora dhtmlx
        if(count($_POST))                                                                                                                    
        {                                                                                                                                    
            foreach($_POST as $key=>$value)                                                                                                  
            {                                                                                                                                
                $parts = explode('_',$key);

                $simid = null;
                                                   
                if(count($parts) > 1)                                                                                                        
                {                                                                                                                            

                    if(end($parts) == 'id') {
                    	$simid = $value;
                    	// удаляем т.к. с флажком сингл может быть только одна запись для текущей статьи
   						$sqlDel = "
   						DELETE FROM pages_similar 
   							WHERE 
   								page_id = :page_id 
   							AND single = 1
   						";
						$query = DB::query(Database::DELETE, $sqlDel);
						$query->parameters(array(
							':page_id' => $page_id
						));
						if($query->execute() > 0)
							LogMaster::log('delete similar is good');
						else
							LogMaster::log('delete similar is fail');
					}

                    if(end($parts) == 'c0') {
					    $sqlUpd = '';
					    // обработка установки чека
                        if((int)$value == 1) {
					        $sqlUpd = "
					        REPLACE INTO pages_similar (
								page_id,
								similar_id,
								single
					         )
					         VALUES(
					           ".$page_id.",
					           {id},
					           1
					        )";

					    }
                    }                                                        
                }                                                        
            }
        }

        $grid->sql->attach("Update", $sqlUpd);

        function clear_cache()
        {
        	Model_Common::$cache->delete_tag('pages_similar');
        }
        $grid->event->attach('afterProcessing', 'clear_cache');
		
		$sql = "
        SELECT
            count(p.id) cnt,
            p.id,
            s1.name section1,
            s2.name section2,
            p.title,
            (CASE WHEN ps.id IS NOT NULL THEN ps.single ELSE 0 END) chk 
         FROM
           pages_tags pt,
           pages p
             LEFT JOIN sections s1 ON s1.id = p.section_id
             LEFT JOIN sections s2 ON s2.id = s1.parent_id AND s1.parent_id <> 0
             LEFT JOIN pages_similar ps ON ps.page_id = ".$page_id." AND ps.similar_id = p.id 
          WHERE
              p.id = pt.page_id
           AND pt.tag_id IN (SELECT tag_id FROM pages_tags WHERE page_id = ".$page_id.")
           AND p.showhide = 1
           AND p.section_id >= 300  #исключаем старые разделы
           AND p.id <> ".$page_id." #исключаем текущую статью
         GROUP BY p.id
         ORDER BY cnt DESC, date DESC
        ";

		$grid->render_sql($sql,'id','chk,section2,section1,title');
	}

	/**
	 * Получение списка похожих материалов по тегам
	 * @param (int) $page_id ИД материала
	 * @param boolean $single_id id записи для синглового блока
	 */
	function get_similars_by_tags($page_id, $single_id = 0)
	{
		$grid = new GridConnector($this->connection);
		$grid->enable_log($this->connectorLog,true);
		$grid->dynamic_loading(20);
		 
		//предварительная обработка записей до рендеринга
		function before_render($item) {
			if ($item->get_index()%2)
				$color = '#E7EDEF';
			else
				$color = '#FFFFFF';
				
			$item->set_row_color($color);
		}
		$grid->event->attach('beforeRender','before_render');

		$page_id = (int)$page_id;
		
		Model_Pages::$page_id = $page_id;

		$sqlUpd = "UPDATE pages_similar SET single = 1 WHERE page_id = {id} AND similar_id = 0 ";  // fake update, stub

		// разбираем вручную данные dataProcessora dhtmlx
        if(count($_POST))                                                                                                                    
        {                                                                                                                                    
            foreach($_POST as $key=>$value)                                                                                                  
            {                                                                                                                                
                $parts = explode('_',$key);

                $simid = null;
                                                   
                if(count($parts) > 1)                                                                                                        
                {                                                                                                                            
                    if(end($parts) == 'c0') {
					    $sqlUpd = '';
					    // обработка установки чека
                        if((int)$value == 1) {
					        $sqlUpd = "
					        REPLACE INTO pages_similar (
								page_id,
								similar_id,
								single
					         )
					         VALUES(
					           ".$page_id.",
					           {id},
					           0
					        )";
					    }
					    // обработка снятия чека
                        if((int)$value == 0) {
        					$sqlUpd = "DELETE FROM pages_similar WHERE page_id = ".$page_id." AND similar_id = {id}";
                        }
                    }                                                        
                }                                                        
            }
        }

        $grid->sql->attach("Update", $sqlUpd);

        function clear_cache()
        {
        	Model_Common::$cache->delete_tag('pages_similar');
        }
        $grid->event->attach('afterProcessing', 'clear_cache');
		
		$sql = "
        SELECT
            count(p.id) cnt,
            p.id,
            s1.name section1,
            s2.name section2,
            p.title,
            (CASE WHEN ps.id IS NOT NULL THEN 1 ELSE 0 END) chk 
         FROM
           pages_tags pt,
           pages p
             LEFT JOIN sections s1 ON s1.id = p.section_id
             LEFT JOIN sections s2 ON s2.id = s1.parent_id AND s1.parent_id <> 0
             LEFT JOIN pages_similar ps ON
             		ps.page_id = ".$page_id." 
             		AND ps.similar_id = p.id 
             		AND ps.single <> 1 
             		AND ps.similar_id <> ".$single_id." 
          WHERE
              p.id = pt.page_id
           AND pt.tag_id IN (SELECT tag_id FROM pages_tags WHERE page_id = ".$page_id.")
           AND p.showhide = 1
           AND p.section_id >= 300  #исключаем старые разделы
           AND p.id <> ".$page_id." #исключаем текущую статью
           AND p.id NOT IN (SELECT similar_id FROM pages_similar WHERE page_id = ".$page_id." AND single = 1) # исключаем материал из single блока
         GROUP BY p.id
         ORDER BY cnt DESC, date DESC
        ";

		$grid->render_sql($sql,'id','chk,section2,section1,title');
	}
}
