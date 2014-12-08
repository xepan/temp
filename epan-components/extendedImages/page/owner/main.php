<?php

class page_extendedImages_page_owner_main extends page_componentBase_page_owner_main {
	function initMainPage(){
		$this->h1->setHTML('<i class="fa fa-picture-o"></i> '.$this->component_name.'<small> Images with magics</small>');
	}


	function page_config(){
		$this->add('H1')->set('Default Config Page');
	}
}