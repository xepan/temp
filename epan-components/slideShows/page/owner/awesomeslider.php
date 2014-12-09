<?php

class page_slideShows_page_owner_awesomeslider extends page_slideShows_page_owner_main{

	function init(){
		parent::init();

		$gallery_model = $this->app->layout->add('slideShows/Model_AwesomeGallery');
		
		$crud=$this->app->layout->add('CRUD');
		$crud->setModel($gallery_model);
		// $crud->add('Controller_FormBeautifier');
		$ref = $crud->addRef('slideShows/AwesomeImages',array('label'=>'Images'));	
		// if($ref)
			// $ref->add('Controller_FormBeautifier');
	}
}