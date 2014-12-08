<?php

namespace xShop;

class View_Tools_AddBlock extends \componentBase\View_Component{
	public $html_attributes=array(); // ONLY Available in server side components
	function init(){
		parent::init();

		$add_blocks = $this->add('xShop/Model_AddBlock');
		$i=0;
		foreach ($add_blocks as $junk) {
			$lister = $this->add('xShop/View_Lister_AddBlock');
			$images = $this->add('xShop/Model_BlockImages');
			$images->addCondition('block_id',$add_blocks->id);
			$lister->setModel($images);
			$lister->addClass('.carousel-'.$i);
			$this->js(true)->carousel('.carousel-'.$i,1000);
			$i++;
		}

	}

	// defined in parent class
	// Template of this tool is view/namespace-ToolName.html
}