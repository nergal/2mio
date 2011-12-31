<?php
/**
 * Model_Blogs
 * 
 * @author sokol, 2011
 * @package btlady-admin
 */

class Model_Blogs extends Model_Common {
    protected $_name = 'blogsmodel';
    public static $selBlog = 0;
    public static $bi_id = 0;
	public static $grid;
	public static $self; 
    
    /**
     * Получить данные для грида 
     */
    public function getBlogs()
    {
		require($this->pathDhtmlx . 'grid_connector.php');
        $blogrid = new GridConnector($this->connection);
        $blogrid->enable_log($this->connectorLog, true);
        $blogrid->dynamic_loading(20);
		
		self::$self = $this;
		self::$grid = $blogrid;
		function my_insert($data){
			$self = Model_Blogs::$self;
			
			$slug = $self->view->transliterate($data->get_value('title'));
			$title = mysql_real_escape_string($data->get_value('title'));
			$description = mysql_real_escape_string($data->get_value('description'));
			$moderated = $data->get_value('moderated');
			$type = $data->get_value('type');
			
			$sql = "INSERT INTO blogs (slug, title, description, user_id, moderated, type, date) " .
				"VALUES ('{$slug}', '{$title}', '{$description}', 0, {$moderated}, {$type}, NOW())";
			
			$self::$grid->sql->query($sql);
			$data->success();
		} 
        
		self::$self = $this;
		function afterInsert($action)
		{
			$id = mysql_insert_id();
			$status = $action->get_status();

			if($status == 'inserted')
				$action->success($id);
		}
        
        function doAfterProcessing()
        {
            //$cache = Zend_Db_Table_Abstract::getDefaultMetadataCache();
            //$cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG, array('blogs'));
        }

        //отправить уведомление владельцу о модерации
        self::$self = $this;
        function beforeUpdate($action)
        {
            if($action->get_value("moderated") == 1)
            {
                $b_id = $action->get_value('id');
                $blog_info = Model_Blogs::$self->getBlogInfo($b_id);
				$self = Model_Blogs::$self;

                if($blog_info['email'] && $blog_info['moderated'] == 0)
                {
					$href = $self->view->uri($self, array('slug' => $blog_info['slug']));
					event::emit('blog_moderated', array('uri' => $href, 'to' => $blog_info['email']));
                }
                
            }
			
			$b_title = str_replace("'", "\'", $action->get_value('title'));
            $action->set_value('title', $b_title);
        }
		
        function doBeforeDelete($action)
        {
            $b_id = $action->get_value("id");
            $blog_info = Model_Blogs::$self->getBlogInfo($b_id);
            
            event::emit('blog_deleted', array('to' => $blog_info['email']));
        }
		
        $blogrid->event->attach("beforeUpdate", 'beforeUpdate');
        $blogrid->event->attach("afterProcessing", 'doAfterProcessing');
		$blogrid->event->attach("beforeDelete", 'doBeforeDelete');
        $blogrid->event->attach("afterInsert", 'afterInsert');
		$blogrid->event->attach("beforeInsert","my_insert"); 
        $blogrid->sql->set_transaction_mode("record");
        $blogrid->render_table('blogs','id','title,description,moderated,type');
    }
    
    /**
     * Получить данные для грида 
     */
    public function getBlogItems($id)
    {
        require($this->pathDhtmlx . 'grid_connector.php');
        
        $blogrid = new GridConnector($this->connection);
        $blogrid->enable_log($this->connectorLog, true);
        $blogrid->dynamic_loading(20);
        
        function color_rows($item)
        {
        	$sid = $item->get_value('id');
        	if ($item->get_index()%2)
                $color = '#E7EDEF';
            else
                $color = '#FFFFFF';

            $item->set_row_color($color);
        }
        $blogrid->event->attach('beforeRender','color_rows');
LogMaster::log('id=', $id);        
        if($id > 0)
        {
	        self::$selBlog = $id;
	        
	        function custom_filter($filter_by) 
	        {
	            if (!count($filter_by->rules) || strpos($filter_by->rules[0],'=')===false) 
	            {
                    if(Model_Blogs::$selBlog > 0)
	            	    $filter_by->add('blog_id', Model_Blogs::$selBlog, '=');
	            }
	        }
	        $blogrid->event->attach('beforeFilter', 'custom_filter');
        }
        
        function beforeProcessing($action)
        {
			$title = str_replace("'", "\'", $action->get_value('title'));
			$action->set_value('title', $title);
			
			$body = str_replace("'", "\'", $action->get_value('body'));
			$action->set_value('body', $body);
		}
        
		$insertSQL = "INSERT INTO blog_items (title, body, blog_id) VALUES ('{title}', '{body}', {$id})";
		
        $blogrid->sql->attach('Insert', $insertSQL);
		$blogrid->event->attach('beforeProcessing', 'beforeProcessing');
        $blogrid->sql->set_transaction_mode("record");
        $blogrid->render_table('blog_items','id','title,body,date');
    }

    /**
     * Получаем список комментов по статье / новости
     */
    public function getComments($id = 0)
    {
        require($this->pathDhtmlx . 'grid_connector.php');
        
        $commentsgrid = new GridConnector($this->connection);
        $commentsgrid->enable_log($this->connectorLog, true);
        $commentsgrid->dynamic_loading(20);
        
        function custom_sort($sorted_by){
            if (!sizeof($sorted_by->rules)){
                $sorted_by->add('date', 'DESC');
            }
        }
        $commentsgrid->event->attach("beforeSort","custom_sort");
            	
        function beforeUpdate($action)
        {
			$escapeQuote = array(
				'text',
				'username'
			);
			
			foreach($escapeQuote as $name)
			{
				$body = str_replace("'", "\'", $action->get_value($name));
				$action->set_value($name, $body);
			}
		}
		
        $commentsgrid->event->attach('beforeUpdate', 'beforeUpdate');
        $commentsgrid->sql->set_transaction_mode("record");

        $deleteSQL = "DELETE FROM blog_comments WHERE id = {id}";
		$updateSQL = "UPDATE blog_comments SET text = '{text}' WHERE id = {id}";
        
        $commentsgrid->sql->attach("Delete", $deleteSQL);
		$commentsgrid->sql->attach("Update", $updateSQL);
        
        $selectSQL = "SELECT bc.id, bc.date, bc.text, u.username " .
				"FROM blog_comments bc " .
				"LEFT JOIN users u ON u.id = bc.user_id " .
				"WHERE blog_item_id = {$id}";

    	$commentsgrid->render_sql($selectSQL,'id','username,date,text');
    }
	
	public function getUsers()
	{
        require($this->pathDhtmlx . 'grid_connector.php');
        
        $grid = new GridConnector($this->connection);
        $grid->enable_log($this->connectorLog, true);
        $grid->dynamic_loading(20);
		
		$grid->render_table('users', 'id','id, username, email');
	}
    
    public function getAuthor($blog_id)
    {
        require($this->pathDhtmlx . 'grid_connector.php');
        
        $grid = new GridConnector($this->connection);
        $grid->enable_log($this->connectorLog, true);
        $grid->dynamic_loading(20);
		
        $selectSQL = "
            SELECT u.id, u.username, u.email
            FROM users u
            LEFT JOIN blogs b ON u.id = b.user_id
            WHERE b.id = {$blog_id}";
            
        $insertSQL = "UPDATE blogs SET user_id = {id} WHERE id = {$blog_id}";
		
        self::$self = $this;
        self::$bi_id = $blog_id;
        function afterInsert()
        {
            $blog_info = Model_Blogs::$self->getBlogInfo(Model_Blogs::$bi_id);
            event::emit('blog_moderated', array('to' => $blog_info['email']));
        }
        
		$grid->event->attach("afterInsert", 'afterInsert');
        $grid->sql->attach('Insert', $insertSQL);
        $grid->render_sql($selectSQL, 'id','id, username, email');
    }
	
    public function getAllTags()
    {
        require($this->pathDhtmlx . 'grid_connector.php');
        
        $grid = new GridConnector($this->connection);
        $grid->enable_log($this->connectorLog, true);
        $grid->dynamic_loading(20);

        function beforeUpdate($action)
        {
			$tag_name = str_replace("'", "\'", $action->get_value('name'));
			$action->set_value('name', $tag_name);
		}
		$grid->event->attach("beforeUpdate", 'beforeUpdate');
        
        $grid->render_table('tags', 'id', 'name');
    }
    
    public function getBindTags($blog_id)
    {
        require($this->pathDhtmlx . 'grid_connector.php');
        
        $grid = new GridConnector($this->connection);
        $grid->enable_log($this->connectorLog, true);
        $grid->dynamic_loading(20);
        
        $selectSQL = "SELECT bt.id, t.name FROM blogs_tags bt LEFT JOIN tags t ON t.id = bt.tag_id WHERE bt.b_id = {$blog_id}";
		$insertSQL = "INSERT INTO blogs_tags (b_id, tag_id) VALUES ({$blog_id}, {id})";
		$deleteSQL = "DELETE FROM blogs_tags WHERE id = {id}";
		
		$grid->sql->attach("Insert", $insertSQL);
		$grid->sql->attach("Delete", $deleteSQL);
		$grid->render_sql($selectSQL, 'id', 'name');
	}
	
    public function getBlogInfo($b_id)
    {
        $b_id = intval($b_id);
        
        $sql = "
			SELECT u.email, b.slug, b.moderated
			FROM blogs b
			LEFT JOIN users u ON u.id = b.user_id
			WHERE b.id = {$b_id}";
            
        $result = $this->fetchAll($sql);
        if($result)
        {
            return current($result);
        }
        
        return array();
    }
}
