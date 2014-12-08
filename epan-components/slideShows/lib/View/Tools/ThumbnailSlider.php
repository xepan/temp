<?php

namespace slideShows;

class View_Tools_ThumbnailSlider extends \componentBase\View_Component{
	public $html_attributes=array(); // ONLY Available in server side components
	function init(){
		parent::init();

		$images=$this->add('slideShows/Model_ThumbnailSliderImages');

		if($this->html_attributes['thumbnailslider_gallery_id']){
				$images->addCondition('gallery_id',$this->html_attributes['thumbnailslider_gallery_id']);
				$lister=$this->add('slideShows/View_Lister_ThumbnailSlider');
				$images->tryLoadAny();	
				$lister->setModel($images);
			}else{
				$this->add('View_Error')->set('Please Select Gallery Group first');
			}

		// $gallery->addCondition('is_publish',true);	
		// $gallery->tryLoadAny();	

		// $images=$this->add('slideShows/Model_ThumbnailSliderImages');
		// // throw new \Exception($this->data_options);
		
		// $images->addCondition('gallery_id',$gallery['id']);	
		// $images->setOrder('order_no','asc');
		// $images->tryLoadAny();
		
		// $lister->setModel($images);
				$slider_css = 'epans/'.$this->api->current_website['name'].'/slider.css';
		$this->api->template->appendHTML('js_include','<link id="slideShows-thumbnail-customcss-link" type="text/css" href="'.$slider_css.'" rel="stylesheet" />'."\n");

	}

	
	// defined in parent class
	// Template of this tool is view/namespace-ToolName.html
}