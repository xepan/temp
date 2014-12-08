<?php

class page_slideShows_page_owner_waterwheel extends page_slideShows_page_owner_main{

	function init(){
		parent::init();

		$gallery_model = $this->app->layout->add('slideShows/Model_WaterWheelGallery');
		
		$crud=$this->app->layout->add('CRUD');
		$crud->setModel($gallery_model);
		// $crud->add('Controller_FormBeautifier');
		$ref = $crud->addRef('slideShows/Model_WaterWheelImages',array('label'=>'Images'));
		// if($ref)
			// $ref->add('Controller_FormBeautifier');
	}
}