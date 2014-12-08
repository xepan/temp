<?php

namespace xShop;

class View_Lister_CustomFields extends \CompleteLister{

	function setModel($model){

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

		return array('view/xShop-CustomField');
	}
}