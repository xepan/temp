<?php

namespace componentBase;

class View_CssOptions extends \View{
	function init(){
		parent::init();
	}

	function render(){
		$this->api->jquery->addStaticStyleSheet('utilities/colorpicker/pick-a-color-1.1.8.min');
		$this->js()
			->_load('utilities/colorpicker/tinycolor-0.9.15.min')
			->_load('utilities/colorpicker/pick-a-color-1.1.8.min')
			->_load('utilities/colorpicker/colorpicker');
			;
		$this->js(true)->_selector('.epan-color-picker')->univ()->xEpanColorPicker();
		parent::render();
	}

	function defaultTemplate(){

		$this->app->pathfinder->base_location->addRelativeLocation(
		    'epan-addons/'.__NAMESPACE__, array(
		        'php'=>'lib',
		        'template'=>'templates',
		        'css'=>'css',
		        'js'=>'js',
		    )
		);
		
		// $l=$this->api->locate('addons',__NAMESPACE__, 'location');
		// $this->api->pathfinder->addLocation(
		// 	$this->api->locate('addons',__NAMESPACE__),
		// 	array(
		//   		'template'=>'templates',
		//   		'css'=>'templates/css'
		// 		)
		// 	)->setParent($l);

		return array('view/default/cssoptions');
	}
}