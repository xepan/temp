<?php

class page_slideShows_page_waterwheelslider extends Page{
	
	function init(){
		parent::init();

		$gallery= $this->add('slideShows/Model_WaterWheelGallery');
		// $cats->addCondition('is_active',true);
		$options= "";
		foreach ($gallery as $junk) {
			$options .= '<option value="'.$gallery['id'].'">'.$gallery['name'].'</option>';
		}

		echo $options;
		exit;
	}
}