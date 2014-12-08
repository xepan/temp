<?php

class page_xImageGallery_page_owner_update extends page_componentBase_page_update {
		
	public $git_path="https://github.com/xepan/xImageGallery"; // Put your components git path here

	function init(){
		parent::init();

		// 
		// Code To run before update
		
		$this->update($dynamic_model_update=false); // All modls will be dynamic executed in here
		$model_array=array('Model_Gallery',
							'Model_Images',
						);
		foreach ($model_array as  $md) {
			$model=$this->add('xImageGallery/'.$md);
			$model->add('dynamic_model/Controller_AutoCreator');
			$model->tryLoadAny();
		}
		$this->add('View_Info')->set('Component Updated Successfully');
		// Code to run after update
	}
}