<?php

namespace slideShows;

class View_Tools_TransformGallery extends \componentBase\View_Component{
	public $html_attributes=array(); // ONLY Available in server side components
	function init(){
		parent::init();
			$images=$this->add('slideShows/Model_TransformGalleryImages');

		if($this->html_attributes['transform_gallery_id']){
				$images->addCondition('gallery_id',$this->html_attributes['transform_gallery_id']);
				$lister=$this->add('slideShows/View_Lister_TransformGallery');
				$images->tryLoadAny();	
				$lister->setModel($images);
			}else{
				$this->add('View_Error')->set('Please Select Gallery Group first');
			}
	}



	// defined in parent class
	// Template of this tool is view/namespace-ToolName.html
}