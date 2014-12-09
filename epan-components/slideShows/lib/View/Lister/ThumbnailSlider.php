<?php
namespace slideShows;

class View_Lister_ThumbnailSlider extends \CompleteLister{
	// public $config;
		function setModel($model){

	$gallery_model=$this->add('slideShows/Model_ThumbnailSliderGallery');
		if(!$model['gallery_id'])
			$this->add('View_Error')->set('Please Add Images First');
		
		else{
			$gallery_model->load($model['gallery_id']);
			// $gallery_model->tryLoadAny();
			$this->template->trySet('direction',$gallery_model['direction']);
			$this->template->trySet('on_hover',$gallery_model['on_hover']);
			$this->template->trySet('scroll_intervarl',$gallery_model['scroll_intervarl']);
			$this->template->trySet('scroll_duration',$gallery_model['scroll_duration']);
			$this->template->trySet('autoAdvance',$gallery_model['autoAdvance']);
			$this->template->trySet('scroll_by_each_thumb',$gallery_model['scroll_by_each_thumb']);
		}
		parent::setModel($model);
				
	}		
	
	function defaultTemplate(){
		$this->app->pathfinder->base_location->addRelativeLocation(
		    'epan-components/'.__NAMESPACE__, array(
		        'php'=>'lib',
		        'template'=>'templates',
		        'css'=>'templates/css',
		        'js'=>'templates/js',
		    )
		);
		
		// $l=$this->api->locate('addons',__NAMESPACE__, 'location');
		// $this->api->pathfinder->addLocation(
		// 	$this->api->locate('addons',__NAMESPACE__),
		// 	array(
		//   		'template'=>'templates',
		//   		'css'=>'templates/css',
		//   		'js'=>'templates/js'
		// 		)
		// 	)->setParent($l);

		return array('view/slideShows-ThumbnailSliders');
	}

}