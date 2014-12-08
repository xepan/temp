<?php

class page_slideShows_page_owner_thumbnailslider extends page_slideShows_page_owner_main{

	function init(){
		parent::init();

		$thumb_model = $this->app->layout->add('slideShows/Model_ThumbnailSliderGallery');
		
		$crud=$this->app->layout->add('CRUD');
		$crud->setModel($thumb_model);
		// $crud->add('Controller_FormBeautifier');
		$ref = $crud->addRef('slideShows/Model_ThumbnailSliderImages',array('label'=>'Images'));
		// if($ref)
			// $ref->add('Controller_FormBeautifier');
	}
}