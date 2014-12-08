<?php

class page_slideShows_page_owner_main extends page_componentBase_page_owner_main {
	function page_index(){
		// $this->add('H1')->set('Component Owner Main Page');
		$this->h1->setHTML('<i class="fa fa-film"></i> '.$this->component_name. '<small>Attractive, Custom and Effectfull Slide Shows</small>');
		$tab=$this->add('Tabs');
		$gallery_tab=$tab->addTabURL('slideShows/page_owner_awesomeslider','Add Awesome Slider Gallery');
		$gallery_tab=$tab->addTabURL('slideShows/page_owner_thumbnailslider','Add Thumbanil Slider Images');
		$gallery_tab=$tab->addTabURL('slideShows/page_owner_waterwheel','Add Water Wheel Slider Images');
		$gallery_tab=$tab->addTabURL('slideShows/page_owner_transformgallery','Add 3D Gallery ');
	}


	function page_config(){
		$this->add('H1')->set('Default Config Page');
	}
}