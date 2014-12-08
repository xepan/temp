<?php

namespace componentBase;
/**
 * This is base component class for All 
 * components
 */
class View_Component extends \View {
	/**
	 * $namespace it contains the namespance of child class
	 * @var String
	 */
	public $namespace=null;
	/**
	 * $items_allowed it contains comma seprated jQuery Selector
	 * and defines the items only allowed to be dropped 
	 * @var String
	 */
	public $items_allowed =null;
	/**
	 * $items_cancelled it contains comma seprated jQuery Selector
	 * and defines the items that are not allowed 
	 * @var String
	 */
	public $items_cancelled=null;
	/**
	 * $component_type just add the attribute in the component tag
	 * for code analysis purpose 
	 * @var String
	 */
	public $component_type;
	/**
	 * $is_resizable Defines whether the component is resizable or not
	 * @var boolean
	 */
	public $is_resizable=false;
	/**
	 * MUST BE DEFINED IN IHERITED CLASSES
	 *  $is_sortable defines weather the component is sortable or not
	 * @var boolean
	 */
	public $is_sortable = null;

	function initializeTemplate(){
		
		$this_class = get_class($this);
		$namespace_class =explode("\\", $this_class);
		$this->namespace = $namespace_class[0];
		preg_match_all("/(.*)_(.*)$/", $namespace_class[1],$matches);
		$this->component_type = $matches[2][0];

		// Add Location for templates in Class Extending / Inherited  Component Base

		$this->app->pathfinder->base_location->addRelativeLocation(
		    'epan-components/'.$this->namespace, array(
		        'php'=>'lib',
		        'template'=>'templates',
		        'css'=>'css',
		        'js'=>'js',
		    )
		);

			// $l=$this->api->locate('addons',$this->namespace, 'location');
			// $this->api->pathfinder->addLocation(
			// $this->api->locate('addons',$this->namespace),
			// array(
		 //  		'template'=>'templates',
		 //  		'css'=>'templates/css',
		 //  		'js'=>'templates/js'
			// 	)
			// )->setParent($l);


		parent::initializeTemplate();
	}

	function recursiveRender(){

		if($this->api->edit_mode){

			// EDIT START
			// Add Location for templates in component base
			// $l=$this->api->locate('addons',__NAMESPACE__, 'location');
			// $this->api->pathfinder->addLocation(
			// $this->api->locate('addons',__NAMESPACE__),
			// array(
		 //  		'template'=>'templates',
		 //  		'css'=>'templates/css',
		 //  		'js'=>'templates/js'
			// 	)
			// )->setParent($l);		
			
			if($this->is_sortable)
				$this->template->append('class','epan-sortable-component ');
				
			if($this->is_resizable)
				$this->template->append('class','ui-resizable');

		}

		parent::recursiveRender();
	}

	function defaultTemplate(){
		if(file_exists($file=getcwd().DS.'epan-components'.DS.$this->namespace.DS.'templates'.DS.'view'.DS.$this->namespace.'-'.str_replace("View_Tools_", "",$this->component_type).'.html'))
			return array('view/'.$this->namespace.'-'.str_replace("View_Tools_", "",$this->component_type));
		else
			return parent::defaultTemplate();
	}

}