<?php

namespace xImageGallery;

class View_Tools_GoogleImageGallery extends \componentBase\View_Component{
	function init(){
		parent::init();

		// echo $this->data_options;
		// exit;
		if($this->data_options == null OR $this->data_options == ""){
			$this->add('View_Error')->set('select gallery or create gallery..by double click on me.');			
			return;
		}

		$image_view=$this->add('xImageGallery/View_Lister_Images');

		$gallery=$this->add('xImageGallery/Model_Gallery');
			
		$gallery->addCondition('name',$this->data_options);
		$gallery->tryLoadAny();	
		$images=$this->add('xImageGallery/Model_Images');
		$images->addCondition('gallery_id',$gallery['id'])->addCondition('is_publish',true);	

		if($_GET['gallery_id'])
			$images->addCondition('gallery_id',$_GET['gallery_id']);	

		//loading custom CSS file	
		$gallery_css = 'epans/'.$this->api->current_website['name'].'/ImageGalleryCustom.css';
		$this->api->template->appendHTML('js_include','<link id="xepan-googleimagegallery-customcss-link" type="text/css" href="'.$gallery_css.'" rel="stylesheet" />'."\n");
		
		if(!$images->count()->getOne()> 0){
			$this->add('View_Error')->set('No Images found in this gallery.. add images to this gallery');
			
		}		
		$image_view->setModel($images);
	}
		
		// defined in parent class
	// Template of this tool is view/namespace-ToolName.html
}