<?php

class page_slideShows_page_transformgallery extends Page{
	
	function init(){
		parent::init();

		$cats= $this->add('slideShows/Model_TransformGallery');
		// $cats->addCondition('is_active',true);
		$options= "";
		foreach ($cats as $junk) {
			$options .= '<option value="'.$cats['id'].'">'.$cats['name'].'</option>';
		}

		echo $options;
		exit;
	}
}