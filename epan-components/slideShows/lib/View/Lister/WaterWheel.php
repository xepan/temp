<?php
namespace slideShows;

class View_Lister_WaterWheel extends \CompleteLister{
	function setModel($model){

		// throw new \Exception($model['gallery_id']);
		$gallery_model=$this->add('slideShows/Model_WaterWheelGallery');
		if(!$model['gallery_id'])
			$this->add('View_Error')->set('Please Add Images First');
		
		else{
			$gallery_model->load($model['gallery_id']);
			$image=$this->add('slideShows/Model_WaterWheelImages');
			// $image->setOrder('start_item');
			// $gallery_model->tryLoadAny();
			$this->template->trySet('show_item',$gallery_model['show_item']);
			$this->template->trySet('speed',$gallery_model['speed']);
			$this->template->trySet('separation',$gallery_model['separation']);
			$this->template->trySet('size_multiplier',$gallery_model['size_multiplier']);
			$this->template->trySet('opacity',$gallery_model['opacity']);
			$this->template->trySet('animation',$gallery_model['animation']);
			$this->template->trySet('autoPlay',$gallery_model['autoPlay']);
			$this->template->trySet('orientation',$gallery_model['orientation']);
			$this->template->trySet('keyboard_Nav',$gallery_model['keyboard_Nav']);
			$this->template->trySet('start_item',$image['start_item']);
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

		return array('view/slideShows-WaterWheelCarousels');
	}

}