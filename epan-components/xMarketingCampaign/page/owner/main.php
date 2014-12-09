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
 
	function page_config(){
		$this->add('H1')->set('Default Config Page');
	}
}