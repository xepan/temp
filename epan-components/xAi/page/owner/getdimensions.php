<?php

class page_xAi_page_owner_getdimensions extends Page {
	
	function init(){
		parent::init();

		$dimensions = $this->add('xAi/Model_Dimension');
		echo json_encode($dimensions->getRows());
		exit;

	}
}