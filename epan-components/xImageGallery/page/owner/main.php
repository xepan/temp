<?php

class page_xImageGallery_page_owner_main extends page_componentBase_page_owner_main {
	function page_index(){

		$this->h1->setHTML('<i class="fa fa-file-picture-o"></i> '.$this->component_name. '<small>Different kinds of Attractive and Custom Image Gallery</small>');

		$tab=$this->add('Tabs');
		$cat_tab=$tab->addTabURL('xImageGallery/page_owner_imageGallery','Google Gallery');
	}


	function page_config(){
		$this->add('H1')->set('Default Config Page');
	}
}