<?php

class page_slideShows_page_owner_thumbnailslider extends page_slideShows_page_owner_main{

	function page_index(){
		// parent::init();

		$thumb_model = $this->add('slideShows/Model_ThumbnailSliderGallery');
		
		$crud=$this->add('CRUD');
		$crud->setModel($thumb_model);
		$crud->add('Controller_FormBeautifier');
		$ref = $crud->addRef('slideShows/Model_ThumbnailSliderImages',array('label'=>'Images'));
		if($ref)
			$ref->add('Controller_FormBeautifier');
	}
}