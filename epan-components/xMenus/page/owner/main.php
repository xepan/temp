<?php

class page_xMenus_page_owner_main extends page_componentBase_page_owner_main {
	function init(){
		parent::init();
		
		$this->app->layout->template->trySetHTML('page_title','<i class="fa fa-navicon"></i> '.$this->component_name. '<small>Different kinds of Responsive and Custom Menus</small>');
		$this->app->layout->add('H3')->setHTML('<small> no option At backend</small>');

		$xmenu_m=$this->app->top_menu->addMenu($this->component_name);
		$xmenu_m->addItem(array('Dashboard','icon'=>'gauge-1'),'xMenus_page_owner_dashboard');
	}


	function page_config(){
		$this->add('H1')->set('Default Config Page');
	}
}