<?php


class page_xAi_page_owner_data_management extends Page {
	
	function init(){
		parent::init();

		$tabs = $this->add('Tabs');
		$meta_data_tab = $tabs->addTabURL('xAi_page_owner_data_metadata','Meta Data Management');



	}
}