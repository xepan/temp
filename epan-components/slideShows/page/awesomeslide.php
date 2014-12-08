<?php

class page_slideShows_page_awesomeslide extends Page{
	
	function init(){
		parent::init();

		$cats= $this->add('slideShows/Model_AwesomeGallery');
		// $cats->addCondition('is_active',true);
		$options= "";
		foreach ($cats as $junk) {
			$options .= '<option value="'.$cats['id'].'">'.$cats['name'].'</option>';
		}

		echo $options;
		exit;
	}
}