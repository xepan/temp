<?php

class page_xMenus_page_owner_main extends page_componentBase_page_owner_main {
	function initMainPage(){

		$this->h1->setHTML('<i class="fa fa-navicon"></i> '.$this->component_name. '<small>Different kinds of Responsive and Custom Menus</small>');
		$this->add('H3')->setHTML('<small> no option At backend</small>');

	}


	function page_config(){
		$this->add('H1')->set('Default Config Page');
	}
}