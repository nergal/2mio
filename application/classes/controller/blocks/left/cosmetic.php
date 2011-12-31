<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Blocks_Left_Cosmetic extends Blocks_Abstract
{
    public function render()
    {
		$type = $this->request->query('type');
		
		$article = ORM::factory('article');
		$cosmetics = $article->get_by_section_id(482, 20);
		
		$images = array();
		foreach ($cosmetics as $cosmetic)
		{
			$images[] = array(
				'image' => $cosmetic->photo,
				'title' => $cosmetic->title,
				'cosmetic' => $cosmetic,
			 );
		}
		
		// Чтобы сделать более похожим на модель
		foreach ($images as & $image) {
			$image = (object) $image;
		}

		$this->template->images = $images;
		$this->template->type = $type;
    }
}
