<?php

class page_developerZone_page_owner_main extends page_componentBase_page_owner_main {

	function init(){
		parent::init();

		$this->app->layout->template->trySetHTML('page_title','<i class="fa fa-wrench"></i> '.$this->component_name .'<small> Helper utility to create new applications </small>');

		$developer_m=$this->app->top_menu->addMenu($this->component_name);
		$developer_m->addItem(array('Dashboard','icon'=>'gauge-1'),'developerZone_page_owner_dashboard');
		$developer_m->addItem(array('New Component','icon'=>'plus'),'developerZone_page_owner_component_new');

		// if(!$this->api->isAjaxOutput()){
		// 	$dh_b= $this->toolbar->addButton( 'Developer Home' );
		// 	$dh_b->setIcon('ui-icon-home');
		// 	$dh_b->js( 'click', $this->js()->univ()->redirect( $this->api->url('developerZone_page_owner_main') ) );
		// 	$ds_b= $this->toolbar->addButton( 'Dashboard' );
		// 	$ds_b->setIcon('ui-icon-home');
		// 	$ds_b->js( 'click', $this->js()->univ()->redirect( $this->api->url('owner_dashboard') ) );
		// }
	}

	

	function page_contribute_to_xepan(){
		$this->api->stickyGET('epan_components_marketplace_id');
		$component = $this->add('Model_MarketPlace');
		$component->Load($_GET['epan_components_marketplace_id']);

		$this->add('H5')->set('Provide Your xEpan Developer Credentials');

		$tabs = $this->add('Tabs');
		$login_tab = $tabs->addTab('Login & Upload');
		$register_tab = $tabs->addTab('Register');

		$form=$login_tab->add('Form');
		$form->addField('line','username');
		$form->addField('password','password');
		$form->addSubmit('Upload');
		if($form->isSubmitted()){
			// Check credentials SSL ???
			// 
		}
	}

	function page_config(){
		$this->add('H1')->set('Default Config Page');
	}
}