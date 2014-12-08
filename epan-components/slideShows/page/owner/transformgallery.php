<?php

class page_slideShows_page_owner_transformgallery extends page_slideShows_page_owner_main{

	function page_index(){
		// parent::init();

		$gallery_model = $this->add('slideShows/Model_TransformGallery');
		
		$crud=$this->add('CRUD');
		$crud->setModel($gallery_model);
		$crud->add('Controller_FormBeautifier');		
		$ref = $crud->addRef('slideShows/TransformGalleryImages',array('label'=>'Images'));
		if($ref)
			$ref->add('Controller_FormBeautifier');	
	}
}