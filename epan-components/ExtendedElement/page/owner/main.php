<?php

class page_ExtendedElement_page_owner_main extends page_componentBase_page_owner_main {
	function initMainPage(){

		$this->h1->setHTML('<i class="glyphicon glyphicon-resize-full"></i> '.$this->component_name. '<small>Extended Elements</small>');
		$this->add('H3')->setHTML('<small>No Options At back end :)</small>');
	}


	function page_config(){
		$this->add('H1')->set('Default Config Page');
	}
}