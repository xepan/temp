<?php

class page_slideShows_page_thumbnailslider extends Page{
	
	function init(){
		parent::init();

		$gallery= $this->add('slideShows/Model_ThumbnailSliderGallery');
		// $cats->addCondition('is_active',true);
		$options= "";
		foreach ($gallery as $junk) {
			$options .= '<option value="'.$gallery['id'].'">'.$gallery['name'].'</option>';
		}

		echo $options;
		exit;
	}
}