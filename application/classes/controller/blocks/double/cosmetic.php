<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Blocks_Double_Cosmetic extends Blocks_Abstract
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
		
		$images = array_merge(array_slice($images, 1, 3), $images);
		
		// Чтобы сделать более похожим на модель
		foreach ($images as & $image) {
			$image = (object) $image;
		}

		$this->template->images = $images;
		$this->template->type = $type;
    }
}
