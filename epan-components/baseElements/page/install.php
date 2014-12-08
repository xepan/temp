<?php

class page_baseElements_page_install extends page_componentBase_page_install {
	function init(){
		parent::init();

		// 
		// Code To run before installing
		
		$this->install();
		
		// Code to run after installation
	}
}