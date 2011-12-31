<?php
/**
 * Модель для баннерки
 *
 * @author nergal
 * @package btlady
 * @subpackage admin
 */

class Model_Banner extends Model_Common {
    public static $cache = NULL;

    protected $bannergrid = NULL;
    protected $placesgrid = NULL;

    public function __construct()
    {
        parent::__construct();

        require_once $this->pathDhtmlx . 'grid_connector.php';

        $this->bannergrid = new GridConnector($this->connection);
        $this->placesgrid = new GridConnector($this->connection);

        $this->bannergrid->event->attach('afterProcessing', array('Model_Banner', 'clearBannerCache'));
        $this->placesgrid->event->attach('afterProcessing', array('Model_Banner', 'clearBannerCache'));

        $this->bannergrid->dynamic_loading(40);
        $this->placesgrid->dynamic_loading(40);

        $this->bannergrid->enable_log(APPPATH . '../etc/logs/admin-banners.log', true);
        $this->placesgrid->enable_log(APPPATH . '../etc/logs/admin-banners.log', true);
    }

    public function getAllBanners()
    {
        $insert_sql = "INSERT INTO `banners`(`id`, `name`, `code`, `hash`, `showhide`) VALUES(NULL, '{name}', '{code}', MD5('{code}'), 1)";

        if (!function_exists('escaped')) {
            function escaped($row)
            {
                $data = $row->get_value('code');
                $row->set_value('code', base64_encode($data));
            }
        }

        if (!function_exists('unbase')) {
            function unbase($action)
            {
                $data = $action->get_value('code');
                $action->set_value('code', str_replace("\n", "\r\n", addslashes(base64_decode($data))));
            }
        }

        if (!function_exists('updatehash')) {
            function updatehash($action)
            {
				global $global_DB;
				$global_DB->query(Database::UPDATE, 'UPDATE `banners` SET `hash` = MD5(`code`)');
            }
        }

		global $global_DB;
		$global_DB = $this->db;


        $this->bannergrid->event->attach("beforeProcessing", "unbase");
        $this->bannergrid->event->attach("beforeRender", "escaped");
    	$this->bannergrid->event->attach('afterProcessing', 'updatehash');

        $this->bannergrid->sql->attach("Insert", $insert_sql);
        $this->bannergrid->render_sql('SELECT `id`, `name`, `code` FROM banners', 'id', 'id, name, code');
    }

    public function getAllPlaces()
    {
        $this->placesgrid->render_table('places', 'id', 'name, description');
    }

    public function getAllJoins($id = 1)
    {
        $insert_sql = "INSERT INTO `banner_places`(`banner_id`, `place_id`) (SELECT `b`.`id`, `p`.`id` FROM `banners` `b`, `places` `p` WHERE `b`.`name` = '{banner_name}' AND `p`.`name` = '{place_name}')";
        $delete_sql = "DELETE FROM `banner_places` WHERE `id` = {id}";

        $sql = "SELECT
        				`bp`.`id`,
                        `p`.`name` `place_name`,
                        `b`.`name` `banner_name`
                FROM `places` `p`
                        LEFT JOIN `banner_places` `bp` ON `p`.`id` = `bp`.`place_id`
                        LEFT JOIN `banners` `b` ON `b`.`id` = `bp`.`banner_id`
                WHERE `p`.`id` = " . (int) $id;

        $this->placesgrid->sql->attach("Insert", $insert_sql);
        $this->placesgrid->sql->attach("Delete", $delete_sql);

        $this->placesgrid->render_sql($sql, 'id', 'id, place_name, banner_name');
    }

    public static function clearBannerCache()
    {
        Model_Common::$cache->delete_tag('banners');
        Model_Common::$cache->delete_tag('banner_places');
        Model_Common::$cache->delete_tag('places');
    }
}