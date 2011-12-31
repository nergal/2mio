 <?php defined('SYSPATH') or die('No direct script access.');

class Controller_Blocks_Left_Section extends Blocks_Abstract
{
    public function render()
    {
    	$section_id = $this->request->query('id');
    	$section = ORM::factory('section', $section_id);
    	$items = ORM::factory('article')->get_tree($section_id, 1, 2);

    	$this->template->section = $section;
    	$this->template->items = $items;
    }
}
