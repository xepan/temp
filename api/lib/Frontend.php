<?php

class Frontend extends App_REST{
	function init() {
		parent::init();

			$this->readConfig('../config-default.php');
			$this->dbConnect();

			$this->addLocation(array(
					'addons'=>array( '../epan-addons', '../epan-components', '../atk4-addons' ) )
			)->setParent( $this->pathfinder->base_location );

			$this->addLocation(array(
	            'page'=>array('../epan-components','../epan-addons')
	        ))->setParent($this->pathfinder->base_location);

	        $this->addLocation(array(
	            'page'=>array('..')
	        ))->setParent($this->pathfinder->base_location);

			$this->add('Controller_PatternRouter')
            ->link('v1/book',array('id','method','arg1','arg2'))
            ->route();
	}
}
