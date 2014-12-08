<?php

class page_xMenus_page_owner_update extends page_componentBase_page_update {
	
	public $git_path = 'https://github.com/xepan/xMenus';

	function init(){
		parent::init();

		// 
		// Code To run before installing
		
		$this->update();
		
		
		$this->add('View_Info')->set('Component Is SuccessFully Updated');
		// Code to run after installation
	}
}