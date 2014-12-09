<?php

class page_xImageGallery_page_owner_main extends page_componentBase_page_owner_main {
	
	function init(){
		parent::init();

		$this->app->layout->template->trySetHTML('page_title','<i class="fa fa-file-picture-o"></i> '.$this->component_name. '<small>Different kinds of Attractive and Custom Image Gallery</small>');

		$ximage_m=$this->app->top_menu->addMenu($this->component_name);
		$ximage_m->addItem(array('Google Gallery','icon'=>'gauge-1'),'xImageGallery/page_owner_imageGallery');
	}


	function page_config(){
		$this->add('H1')->set('Default Config Page');
	}
}