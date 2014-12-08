<?php
namespace slideShows;

class View_Lister_TransformGallery extends \CompleteLister{
	function setModel($model){

		// throw new \Exception($model['gallery_id']);
		$gallery_model=$this->add('slideShows/Model_TransformGallery');
		if(!$model['gallery_id'])
			$this->add('View_Error')->set('Please Add Images First');
		
		else{
			$gallery_model->load($model['gallery_id']);
			$gallery_model->tryLoadAny();
			$this->template->trySet('autoplay',$gallery_model['autoplay']);
			$this->template->trySet('interval',$gallery_model['interval']);
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

		return array('view/slideShows-TransformGallery1');
	}

}