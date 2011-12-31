<?php
/**
 * Model_Redirects
 * 
 * @author kolex, 2011
 * @package btlady-admin
 */

class Model_Redirects extends Model_Common {
    protected $_name = 'redirectsmodel';
    public static $selBlog = 0;
    public static $bi_id = 0;
	public static $grid;
	public static $self; 
    
    /**
     * Получить данные для грида 
     */
    public function getRedirects()
    {
		require($this->pathDhtmlx . 'grid_connector.php');
        $grid = new GridConnector($this->connection);
        $grid->enable_log($this->connectorLog, true);
        $grid->dynamic_loading(20);
        $grid->sql->set_transaction_mode("record");
        
		//добавление сортировки
		function custom_sort($sorted_by) {
			if (!sizeof($sorted_by->rules)) {
				$sorted_by->add('id', 'DESC');
			}
		}
		$grid->event->attach('beforeSort', 'custom_sort');
        
        
        $grid->render_table('redirects','id','source,destanation');
    }

}
