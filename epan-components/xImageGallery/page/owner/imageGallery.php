<?php

class page_xImageGallery_page_owner_imageGallery extends page_xImageGallery_page_owner_main{

	function init(){
		parent::init();

		$gallery_model = $this->app->layout->add('xImageGallery/Model_Gallery');
		
		$crud=$this->app->layout->add('CRUD');
		$crud->setModel($gallery_model);
		// $crud->add('Controller_FormBeautifier');
		$ref = $crud->addRef('xImageGallery/Images',array('label'=>'Images'));
		// if($ref)
			// $ref->add('Controller_FormBeautifier');
	}
}