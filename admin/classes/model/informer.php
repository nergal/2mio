<?php
/**
 * Модель для RSS информеров
 *
 * @author tretyak
 * @package btlady
 * @subpackage admin
 */

class Model_Informer extends Model_Common {
    
    public static $cache = NULL;
    public static $self; // указатель на себя же
    public static $id; // для передачи параметра в функцию

    public function __construct()
    {
        parent::__construct();

		if(!class_exists('GridConnector'))
		{
			require_once $this->pathDhtmlx . 'grid_connector.php';
		}        
		
		$this->pagesgrid = new GridConnector($this->connection);
		$this->pagesgrid->dynamic_loading(100);
		$this->pagesgrid->enable_log(APPPATH . '../etc/logs/admin-informers.log', true);
		
		$this->rssgrid = new GridConnector($this->connection);
		$this->rssgrid->dynamic_loading(20);
		$this->rssgrid->enable_log(APPPATH . '../etc/logs/admin-informers.log', true);
		
		self::$self = $this;
    }
    
	/**
	 * Получить список материалов по id раздела
	 */
	public function get_pages($section_id = null)
	{
		//расцветка записей в гриде
		function color_rows($item) {
			$fixed = $item->get_value('fix');
			 
            if((int)$fixed == 1)                                                                                                                                                
            {                                                                                                                                                                   
                $color = '#00ECEC';
            } else {                                                                                                                                                              
				if ($item->get_index()%2)
					$color = '#E7EDEF';
				else
					$color = '#FFFFFF';
            }
				
			$item->set_row_color($color);
		}
		$this->pagesgrid->event->attach('beforeRender','color_rows');
		
		self::$id = $section_id; //делаем section_id видимым из функции
		
		function filter_custom($filter_by)
		{
			if ((!count($filter_by->rules) || strpos($filter_by->rules[0],'LIKE ')===false) && (int)Model_Informer::$id > 0)
			{
				$filter_by->add('section_id', Model_Informer::$id, '=');
			}
		}
		$this->pagesgrid->event->attach('beforeFilter', 'filter_custom');
		
		function custom_sort($sorted_by){
			if (!sizeof($sorted_by->rules)){
				$sorted_by->add('date', 'DESC');
			}
		}
		$this->pagesgrid->event->attach("beforeSort","custom_sort");
		
		$this->pagesgrid->render_table('pages','id', 'title, date, photo, section_id, name_url, showhide, fix, type_id');
	}    
    
	/**
	 * Получить список rss материалов по id rss раздела
	 */
    public function get_rss($rss_category_id){
		
		if($this->rssgrid->is_select_mode()){
			
			self::$id = $rss_category_id; //делаем section_id видимым из функции			
			function custom_filter($filter_by){
				if (!count($filter_by->rules) || strpos($filter_by->rules[0],'LIKE ')===false){
					$filter_by->add('rss_category_id', Model_Informer::$id, '=');
				}
			}
	        
	        function custom_sort($sorted_by){
    	        if (!sizeof($sorted_by->rules)){
        	        $sorted_by->add("rc.order_id", 'ASC');
	            }
    	    }
        	
        	$this->rssgrid->event->attach("beforeSort","custom_sort");
       		$this->rssgrid->event->attach("beforeFilter", "custom_filter");
       		
			$sql = "SELECT rc.id, rc.order_id, rc.title, p.date FROM rss_news as rc LEFT JOIN pages as p ON rc.page_id = p.id";
    	    $this->rssgrid->render_sql($sql, 'id', 'title,date,order_id');
		}else{
			// TODO: порядок полей в гридах материалов и rss не совпадает, пофиксить
			$this->rssgrid->sql->attach('Insert', "insert into rss_news (rss_category_id, page_id, title, order_id) values('$rss_category_id', '{id}', '{page_id}','{order_id}')");
			$this->rssgrid->sql->attach('Update', "update rss_news set order_id={order_id}, title='{page_id}' where id='{id}'");
			$this->rssgrid->sql->attach('Delete', "delete from rss_news where id={id}");
	   	    $this->rssgrid->render_table('rss_categories_news', 'id', 'page_id,,order_id');
		}
	}	
}
