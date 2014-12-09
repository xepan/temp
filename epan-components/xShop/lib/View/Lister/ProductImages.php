<?php

namespace xShop;
class View_Lister_ProductImages extends \CompleteLister{


	function setModel($model){
		parent::setModel($model);
		
		// cloneing the model and set first images  
		$one_image = clone $model;
		$one_image->tryLoadAny();
		$this->template->trySet('zoom3_image_url',$one_image['image_url']?:"epan-components/xShop/templates/images/item_no_image.png");
			
	}
	// function formatRow(){
	// 	$this->current_row['zoom3_image_url'] = $this->model['image_url'];

	// }

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

		return array('view/xShop-ProductImage');
	}
}