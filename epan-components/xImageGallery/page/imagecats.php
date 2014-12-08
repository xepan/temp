<?php

class page_xImageGallery_page_imagecats extends Page{
	
	function init(){
		parent::init();
		$cats= $this->add('xImageGallery/Model_Gallery');
		// $cats->addCondition('is_active',true);
		$options= "";
		foreach ($cats as $junk) {
			$options .= '<option value="'.$cats['name'].'">'.$cats['name'].'</option>';
		}

		echo $options;
		exit;
	}
}