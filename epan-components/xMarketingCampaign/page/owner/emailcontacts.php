<?php

class page_xMarketingCampaign_page_owner_emailcontacts extends page_xMarketingCampaign_page_owner_main{

	function init(){
		parent::init();
	
		$email_category_model = $this->add('xEnquiryNSubscription/Model_SubscriptionCategories');
		$email_category_model->hasMany('xMarketingCampaign/DataSearchPhrase','subscription_category_id');



		$bg=$this->app->layout->add('View_BadgeGroup');
		$data =$this->add('xMarketingCampaign/Model_DataSearchPhrase')->count()->getOne();
		$v=$bg->add('View_Badge')->set('Total Phrases')->setCount($data)->setCountSwatch('ink');
		
		$data =$this->add('xMarketingCampaign/Model_DataSearchPhrase')->addCondition('is_active',true)->count()->getOne();
		$v=$bg->add('View_Badge')->set('Un Grabbed Phrases')->setCount($data)->setCountSwatch('red');
		
		$data =$this->add('xEnquiryNSubscription/Model_Subscription')->count()->getOne();
		$v=$bg->add('View_Badge')->set('Total Emails')->setCount($data)->setCountSwatch('ink');
		
		$data =$this->add('xEnquiryNSubscription/Model_Subscription')->addCondition('from_app','DataGrabberPhrase')->count()->getOne();
		$v=$bg->add('View_Badge')->set('Total Grabbed Emails')->setCount($data)->setCountSwatch('green');

		$data =$this->add('xEnquiryNSubscription/Model_Subscription')->addCondition('from_app','DataGrabberPhrase')->addCondition('is_ok',false)->count()->getOne();
		$v=$bg->add('View_Badge')->set('Total Bounced Emails')->setCount($data)->setCountSwatch('red');

		$data =$this->add('xEnquiryNSubscription/Model_Subscription')->addCondition('from_app','<>','DataGrabberPhrase')->count()->getOne();
		$v=$bg->add('View_Badge')->set('Total Other Emails')->setCount($data)->setCountSwatch('ink');

		$email_category_model->addExpression('total_phrases')->set(function($m,$q){
			return $m->refSQL('xMarketingCampaign/DataSearchPhrase')->count();
		})->type('int');

		$email_category_model->addExpression('active_phrases')->set(function($m,$q){
			return $m->refSQL('xMarketingCampaign/DataSearchPhrase')->addCondition('is_active',true)->count();
		})->type('int');

		$email_category_model->addExpression('grabbed_emails')->set(function($m,$q){

			$emails = $m->add('xEnquiryNSubscription/Model_Subscription',array('table_alias'=>'subs'));
			$emails->addCondition('from_app','DataGrabberPhrase');
			$phrase_j = $emails->join('xMarketingCampaign_data_search_phrase','from_id');
			$phrase_j->addField('subscription_category_id');
			$emails->addCondition('subscription_category_id',$q->getField('id'));

			return $emails->count();
		});

		$email_category_model->addExpression('emails_by_other_apps')->set(function($m,$q){
			$emails = $m->add('xEnquiryNSubscription/Model_Subscription',array('table_alias'=>'subs'));
			$emails->addCondition('from_app','<>','DataGrabberPhrase');
			$phrase_j = $emails->join('xMarketingCampaign_data_search_phrase','from_id');
			$phrase_j->addField('subscription_category_id');
			$emails->addCondition('subscription_category_id',$q->getField('id'));

			return $emails->count();
		});

		$email_category_model->addExpression('bounced_emails')->set(function($m,$q){
			return "'sdsd'";
		});

		$crud = $this->app->layout->add('CRUD');
		$crud->setModel($email_category_model,array('name','is_active','total_phrases','active_phrases','total_emails','grabbed_emails','emails_by_other_apps','bounced_emails'));

		if(!$crud->isEditing()){
			$g=$crud->grid;	
			$crud->add_button->setIcon('ui-icon-plusthick');
		}

		// $crud->add('Controller_FormBeautifier');
		if($crud and (!$crud->isEditing())){
			$g = $crud->grid;
			$g->addColumn('expander','emails');
		}
		
	}

	function page_emails(){
		$group_id = $this->api->stickyGET('xEnquiryNSubscription_Subscription_Categories_id');
		$subs_crud = $this->add('CRUD');
		$cat_sub_model = $this->add('xEnquiryNSubscription/Model_SubscriptionCategoryAssociation')->addCondition('category_id',$group_id);

		$tmp = $cat_sub_model->getElement('subscriber_id')->getModel();
		$tmp->getElement('from_app')->defaultValue('xMarketingCampaign');

		$subs_crud->setModel($cat_sub_model);

		// if($subs_crud){
		// $subs_crud->add('Controller_FormBeautifier');			
		// ->getElement('from_app')->defaultValue('xMarketingCampaign');
		// }
		if($subs_crud and (!$subs_crud->isEditing())){
			$g=$subs_crud->grid;
			$subs_crud->add_button->setIcon('ui-icon-plusthick');
			$g->add_sno();
			$g->addPaginator(100);
			$g->addQuickSearch(array('email'));
		}
	}
}