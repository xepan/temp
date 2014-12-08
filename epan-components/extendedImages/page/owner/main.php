<?php

class page_extendedImages_page_owner_main extends page_componentBase_page_owner_main {
	function initMainPage(){
		
		$this->app->layout->template->trySetHTML('page_title','<i class="fa fa-picture-o"></i> '.$this->component_name.'<small> Images with magics</small>');		
		$this->app->layout->add('H3')->setHTML('<small>No Options At back end :)</small>');
		
		$xextended_images_m=$this->app->top_menu->addMenu($this->component_name);
		$xextended_images_m->addItem(array('Dashboard','icon'=>'gauge-1'),'extendedImages_page_owner_dashboard');
	}


	function page_config(){
		$this->add('H1')->set('Default Config Page');
	}
}