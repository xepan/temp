<?php

class page_ExtendedElement_page_owner_main extends page_componentBase_page_owner_main {
	function initMainPage(){

		$this->app->layout->template->trySetHTML('page_title','<i class="glyphicon glyphicon-resize-full"></i> '.$this->component_name. '<small>Extended Elements</small>');
		$this->app->layout->add('H3')->setHTML('<small>No Options At back end :)</small>');
		
		$xelement_m=$this->app->top_menu->addMenu($this->component_name);
		$xelement_m->addItem(array('Dashboard','icon'=>'gauge-1'),'ExtendedElement_page_owner_dashboard');
	}


	function page_config(){
		$this->add('H1')->set('Default Config Page');
	}
}