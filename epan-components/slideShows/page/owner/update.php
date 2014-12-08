<?php

class page_slideShows_page_owner_update extends page_componentBase_page_update {
		
	public $git_path='https://github.com/xepan/slideShows'; // Put your components git path here

	function init(){
		parent::init();

		// 
		// Code To run before update
		
		$this->update($dynamic_model_update=false); // All modls will be dynamic executed in here
		$model_array=array('Model_AwesomeGallery',
							'Model_AwesomeImages',
							'Model_ThumbnailSliderGallery',
							'Model_ThumbnailSliderImages',
							'Model_TransformGallery',
							'Model_TransformGalleryImages',
							'Model_WaterWheelGallery',
							'Model_WaterWheelImages'
						);
		foreach ($model_array as  $md) {
			$model=$this->add('slideShows/'.$md);
			$model->add('dynamic_model/Controller_AutoCreator');
			$model->tryLoadAny();
		}
		$this->add('View_Info')->set('Component Updated Successfully');
	
		// Code to run after update
	}
}