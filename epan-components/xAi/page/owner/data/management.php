<?php


class page_xAi_page_owner_data_management extends page_xAi_page_owner_main {
	
	function init(){
		parent::init();

		$tabs = $this->app->layout->add('Tabs');
		$meta_data_tab = $tabs->addTabURL('xAi_page_owner_data_metadata','Meta Data Management');


	}
}