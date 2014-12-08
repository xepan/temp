<?php

class page_slideShows_page_owner_main extends page_componentBase_page_owner_main {
	function page_index(){

		$this->app->layout->template->trySetHTML('page_title','<i class="fa fa-bullhorn"></i> '.$this->component_name. '<small> Attractive, Custom and Effectfull Slide Shows</small>');
		
		$slider=$this->app->top_menu->addMenu($this->component_name);
		$slider->addItem(array('Awesome Slider Gallery','icon'=>'gauge-1'),'slideShows/page_owner_awesomeslider');
		$slider->addItem(array('Thumbanil Slider Images','icon'=>'gauge-1'),'slideShows/page_owner_thumbnailslider');
		$slider->addItem(array('Water Wheel Slider Images','icon'=>'gauge-1'),'slideShows/page_owner_waterwheel');
		$slider->addItem(array('3D Gallery','icon'=>'gauge-1'),'slideShows/page_owner_transformgallery');	
	}


	function page_config(){
		$this->add('H1')->set('Default Config Page');
	}

}