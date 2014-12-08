<?php

namespace xAi;

class View_Tools_EngagingText extends \componentBase\View_Component{
	public $html_attributes=array(); // ONLY Available in server side components
	function init(){
		parent::init();

		$this->setAttr('slicePoint',30);
		$this->setAttr('showWordCount','true');
		$this->setAttr('widow',2);
		$this->setAttr('expandEffect','show');
		$this->setAttr('userCollapseText','[^]');
		
		$this->setAttr('expandText','read more');
		$this->setAttr('summaryClass','summary');
		$this->setAttr('detailClass','details');
		$this->setAttr('userCollapse','true');
		$this->setAttr('collapseTimer','0');

	}

	// defined in parent class
	// Template of this tool is view/namespace-ToolName.html
}