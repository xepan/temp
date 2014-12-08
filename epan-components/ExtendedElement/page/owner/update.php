<?php

class page_ExtendedElement_page_owner_update extends page_componentBase_page_update {
		
	public $git_path="https://github.com/xepan/ExtendedElement"; // Put your components git path here

	function init(){
		parent::init();

		// 
		// Code To run before update
		
		$this->update($dynamic_model_update=true); // All modls will be dynamic executed in here
		$this->add('View_Info')->set('Component Updated');
		// Code to run after update
	}
}