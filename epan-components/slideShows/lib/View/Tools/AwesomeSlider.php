<?php

namespace slideShows;

class View_Tools_AwesomeSlider extends \componentBase\View_Component{
	var $data_options;
	var $html_attributes;

	function init(){
		parent::init();

		$image_view=$this->add('slideShows/View_Lister_AwesomeSlider',array('theme_css'=>$this->html_attributes['awesomeslider-theme']?:'theme-default'));

		$gallery=$this->add('slideShows/Model_AwesomeGallery');
		
		if($this->data_options){
			$gallery->addCondition('id',$this->data_options);
			}

		$gallery->addCondition('is_publish',true);	
		$gallery->tryLoadAny();	

		$images=$this->add('slideShows/Model_AwesomeImages');
		// throw new \Exception($this->data_options);
		
		$images->addCondition('gallery_id',$gallery['id']);	
		$images->setOrder('order_no','asc');
		$images->tryLoadAny();
		
		$image_view->setModel($images);
	}

	// defined in parent class
	// Template of this tool is view/namespace-ToolName.html
}