<?php

namespace xAi;

class View_Tools_IntelligentBlock extends \componentBase\View_Component{
	public $html_attributes=array(); // ONLY Available in server side components
	
	function init(){
		parent::init();

		$this->setAttr('data-dimension-id',$this->add('xAi/Model_Dimension')->tryLoadAny()->get('id'));
		$this->setAttr('data-aitrack','Visible');
	}

	// defined in parent class
	// Template of this tool is view/namespace-ToolName.html
}