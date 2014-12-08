<?php

class page_xImageGallery_page_install extends page_componentBase_page_install {
	function init(){
		parent::init();

		// 
		// Code To run before installing
		
		$this->install();
			
		// Code to run after installation

		$css_file = getcwd().DS.'epans/'.$this->api->current_website['name'].'/ImageGalleryCustom.css';
		$css_file_orig = getcwd().DS.'epan-components/xImageGallery/templates/css/ImageGalleryComponent.css';
		
		if(!file_exists($css_file)){
			$css_content_orig = file_get_contents($css_file_orig);
			// throw new \Exception($css_content_orig);
			file_put_contents($css_file, "$css_content_orig");			
			$this->api->template->appendHTML('js_include','<link id="xepan-googleimagegallery-customcss-link" type="text/css" href="'.$css_file.'" rel="stylesheet" />'."\n");
		}


		$model_array=array('Model_Gallery',
							'Model_Images',
						);
		foreach ($model_array as  $md) {
			$model=$this->add('xImageGallery/'.$md);
			$model->add('dynamic_model/Controller_AutoCreator');
			$model->tryLoadAny();
		}

	}
}