<?php

class page_xMarketingCampaign_page_owner_main extends page_componentBase_page_owner_main {

	function init(){
		parent::init();

		$this->app->layout->template->trySetHTML('page_title','<i class="fa fa-slideshare"></i> '.$this->component_name. '<small> Email & Social Campaign Manager</small>');
		
		$xmrkt_cmg_m=$this->app->top_menu->addMenu($this->component_name);
		$xmrkt_cmg_m->addItem(array('Dashboard','icon'=>'gauge-1'),'xMarketingCampaign_page_owner_dashboard');
		$xmrkt_cmg_m->addItem(array('Manage Contacts','icon'=>'gauge-1'),'xMarketingCampaign_page_owner_emailcontacts');
		$xmrkt_cmg_m->addItem(array('Manage NewsLetters','icon'=>'gauge-1'),'xMarketingCampaign_page_owner_newsletters');
		$xmrkt_cmg_m->addItem(array('Add SocialContent','icon'=>'gauge-1'),'xMarketingCampaign_page_owner_socialcontents');
		$xmrkt_cmg_m->addItem(array('Campaigns','icon'=>'gauge-1'),'xMarketingCampaign_page_owner_campaigns');
		$xmrkt_cmg_m->addItem(array('Scheduled Jobs','icon'=>'gauge-1'),'xMarketingCampaign_page_owner_scheduledjobs');
		$xmrkt_cmg_m->addItem(array('Configurations','icon'=>'gauge-1'),'xMarketingCampaign_page_owner_config');

	}

	// function initMainPage(){
		
	// 	$tabs = $this->add('Tabs');
	// 	$tabs->addTabUrl('xMarketingCampaign/page_owner_dashboard','<i class="fa fa-dashboard"></i> Dashboard');
	// 	$tabs->addTabUrl('xMarketingCampaign/page_owner_emailcontacts','<i class="fa fa-users"></i> Manage Contacts');
	// 	$tabs->addTabUrl('xMarketingCampaign/page_owner_newsletters','<i class="fa fa-envelope"></i> Manage NewsLetters');
	// 	$tabs->addTabUrl('xMarketingCampaign/page_owner_socialcontents','<i class="fa fa-share-alt-square"></i> Add SocialContent');
	// 	$tabs->addTabUrl('xMarketingCampaign/page_owner_campaigns','<i class="fa fa-calendar-o"></i> Campaigns');
	// 	$tabs->addTabUrl('xMarketingCampaign/page_owner_scheduledjobs','<i class="fa fa-tasks"></i> Scheduled Jobs');
	// 	$tabs->addTabUrl('xMarketingCampaign/page_owner_config','<i class="fa fa-cogs"></i> Configurations');

	// }

 
	function page_config(){
		$this->add('H1')->set('Default Config Page');
	}
}