<?php


class page_xAi_page_owner_information_management extends page_xAi_page_owner_main {
	
	function page_index(){

		$tabs = $this->app->layout->add('Tabs');
		$meta_data_tab = $tabs->addTabURL('xAi_page_owner_information_extract','Information Extraction');
		
		$meta_info_tab = $tabs->addTabURL('./meta_info','Meta Information');
		
	}

	function page_meta_info(){
		$grid = $this->add('Grid');
		$grid->setModel('xAi/MetaInformation');
	}

}