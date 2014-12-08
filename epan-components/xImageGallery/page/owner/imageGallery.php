<?php

class page_xImageGallery_page_owner_imageGallery extends page_xImageGallery_page_owner_main{

	function page_index(){
		// parent::init();

		$gallery_model = $this->add('xImageGallery/Model_Gallery');
		
		$crud=$this->add('CRUD');
		$crud->setModel($gallery_model);
		$crud->add('Controller_FormBeautifier');
		$ref = $crud->addRef('xImageGallery/Images',array('label'=>'Images'));
		if($ref)
			$ref->add('Controller_FormBeautifier');
	}
}