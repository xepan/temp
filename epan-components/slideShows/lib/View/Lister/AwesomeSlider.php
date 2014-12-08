<?php
namespace slideShows;

class View_Lister_AwesomeSlider extends \CompleteLister{
	public $config;
	function setModel($model){

		// throw new \Exception($model['gallery_id']);
		$gallery_model=$this->add('slideShows/Model_AwesomeGallery');
		if(!$model['gallery_id'])
			$this->add('View_Error')->set('Please Add Images First');
		
		else{
			$gallery_model->load($model['gallery_id']);
			// $gallery_model->tryLoadAny();
			$this->template->trySet('pause_time',$gallery_model['pause_time']);
			$this->template->trySet('control_nav',$gallery_model['control_nav']);
			$this->template->trySet('on_hover',$gallery_model['on_hover']);
			$this->template->trySet('image_paginator',$gallery_model['image_paginator']);
			$this->template->trySet('theme_css',$this->theme_css);
		}
		parent::setModel($model);
				
	}	
	
	function defaultTemplate(){
		$l=$this->api->locate('addons',__NAMESPACE__, 'location');
		$this->api->pathfinder->addLocation(
			$this->api->locate('addons',__NAMESPACE__),
			array(
		  		'template'=>'templates',
		  		'css'=>'templates/css',
		  		'js'=>'templates/js'
				)
			)->setParent($l);

		return array('view/slideShows-AwesomeSlider1');
	}

}