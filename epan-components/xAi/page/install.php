<?php

class page_xAi_page_install extends page_componentBase_page_install {
	function init(){
		parent::init();

		// 
		// Code To run before installing
		
		$this->install();
		
		$default_dimesion = $this->add('xAi/Model_Dimension');
		$default_dimesion['name'] = 'Default';
		$default_dimesion->save();

		// Code to run after installation
	}
}