<?php

class page_developerZone_page_owner_component_edit extends page_developerZone_page_owner_main {
	function init(){
		parent::init();

		$this->api->stickyGET('component');
		$this->add('H3')->setHTML('Editing <u>' . $_GET['component']. '</u> Component');

		$tabs = $this->add('Tabs');
		$tools_tab = $tabs->addTab('Tools');
		$plugins_tab = $tabs->addTab('Plugins');

		$component = $this->add('Model_MarketPlace');
		$component->isInstalling = true ; // To avoid copying files before save
		$component->loadBy('namespace',$_GET['component']);

		$crud = $tools_tab->add('CRUD');
		$tools_model = $component->ref('Tools');
		if($crud->isEditing('edit'))
			$tools_model->isInstalling = true ; // To avoid copying files before save
		$crud->setModel($tools_model);
		$crud->add('Controller_FormBeautifier');

		$crud2 = $plugins_tab->add('CRUD');
		$plug_model = $component->ref('Plugins');
		if($crud2->isEditing('edit'))
			$plug_model->isInstalling = true ;// To avoid copying files before save
		
		$crud2->setModel($plug_model);
		$crud2->add('Controller_FormBeautifier');

	}
}