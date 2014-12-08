<?php

class page_xMarketingCampaign_page_owner_newsletters extends page_xMarketingCampaign_page_owner_main{

	function init(){
		parent::init();

		$newsletter_model = $this->add('xEnquiryNSubscription/Model_NewsLetter');

		$crud = $this->app->layout->add('CRUD');
		$crud->setModel($newsletter_model,null,null);
		// $crud->add('Controller_FormBeautifier');
		
		if(!$crud->isEditing()){
			$crud->add_button->setIcon('ui-icon-plusthick');
		}

	}
}		