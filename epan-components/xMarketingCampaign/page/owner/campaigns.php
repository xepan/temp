<?php

class page_xMarketingCampaign_page_owner_campaigns extends page_xMarketingCampaign_page_owner_main{

	function init(){
		parent::init();

		$bg=$this->app->layout->add('View_BadgeGroup');
		$data=$this->add('xEnquiryNSubscription/Model_NewsLetter')->count()->getOne();
		$v=$bg->add('View_Badge')->set('Total NewsLetters')->setCount($data)->setCountSwatch('ink');

		$data=$this->add('xEnquiryNSubscription/Model_NewsLetter')->addCondition('created_by','xMarketingCampaign')->count()->getOne();
		$v=$bg->add('View_Badge')->set('By This App')->setCount($data)->setCountSwatch('ink');

		$cols = $this->app->layout->add('Columns');
		$cat_col = $cols->addColumn(3);
		$camp_col = $cols->addColumn(9);

		$cat_crud = $cat_col->add('CRUD');
		$cat_model = $this->add('xMarketingCampaign/Model_CampaignCategory');
		$cat_crud->setModel($cat_model,array('name','campaigns'));
		
		if(!$cat_crud->isEditing()){
			$g=$cat_crud->grid;
			$g->addMethod('format_filtercampaign',function($g,$f)use($camp_col){
				$g->current_row_html[$f]='<a href="javascript:void(0)" onclick="'. $camp_col->js()->reload(array('category_id'=>$g->model->id)) .'">'.$g->current_row[$f].'</a>';
			});
			$g->addFormatter('name','filtercampaign');
			$g->add_sno();
		}

		$campaign_model = $this->add('xMarketingCampaign/Model_Campaign');


		//filter Campaigns as per selected category
		if($_GET['category_id']){
			$this->api->stickyGET('category_id');
			$filter_box = $camp_col->add('View_Box')->setHTML('Campaigns for <b>'. $cat_model->load($_GET['category_id'])->get('name').'</b>' );
			
			$filter_box->add('Icon',null,'Button')
            ->addComponents(array('size'=>'mega'))
            ->set('cancel-1')
            ->addStyle(array('cursor'=>'pointer'))
            ->on('click',function($js) use($filter_box,$camp_col) {
                $filter_box->api->stickyForget('category_id');
                return $filter_box->js(null,$camp_col->js()->reload())->hide()->execute();
            });

			$campaign_model->addCondition('category_id',$_GET['category_id']);
		}

		$campaign_crud = $camp_col->add('CRUD');
		$campaign_crud->setModel($campaign_model,null,array('category','name','starting_date','ending_date','effective_start_date','is_active'));
		
		if(!$campaign_crud->isEditing()){
			$campaign_crud->grid->addColumn('expander','schedule');

			$campaign_crud->grid->addColumn('expander','AddEmails','Add Subscription Category');
			$campaign_crud->grid->addColumn('expander','NewsLetterSubCampaign','News Letters To send');
			$campaign_crud->grid->addColumn('expander','social_campaigns','Social Posts To Include');
			// $btn=$campaign_crud->grid->addButton('Schedule Emails Now');
			// $btn->setIcon('ui-icon-seek-end');
			// $btn->js('click')->univ()->frameURL('Campaign Executing',$this->api->url('xMarketingCampaign_page_owner_campaignexec'));
	
			$campaign_crud->add_button->setIcon('ui-icon-plusthick');
			// $Campaign_crud->grid->addColumn('expander','BlogSubCampaign');
		}

		// $Campaign_crud->add('Controller_FormBeautifier');

	}	

	function page_schedule(){
		$campaign_id = $this->api->StickyGET('xmarketingcampaign_campaigns_id');	
		$this->add('View_Box')->set("Campaign Scheduler Campaign Id".$campaign_id);
	}


	function page_AddEmails(){
		$campaign_id = $this->api->StickyGET('xmarketingcampaign_campaigns_id');

		// $v=$this->add('View');
		// $v->addClass('panel panel-default');
		// $v->setStyle('padding','20px');
		
		$grid = $this->add('Grid');

		$cat_model = $this->add('xEnquiryNSubscription/Model_SubscriptionCategories');
		$cat_model->addCondition('is_active',true);

		$cat_model->addExpression('status')->set(function($m,$q)use($campaign_id){
			$category_campaign_model = $m->add('xMarketingCampaign/Model_CampaignSubscriptionCategory',array('table_alias'=>'c'));
			$category_campaign_model->addCondition('category_id',$q->getField('id'));
			$category_campaign_model->addCondition('campaign_id',$campaign_id);
			return $category_campaign_model->count();
		})->type('boolean');

		$grid->setModel($cat_model,array('name','is_associate','status'));
		$grid->addColumn('Button','save','Swap Select');

		if($_GET['save']){
			$campaignemail_model = $this->add('xMarketingCampaign/Model_CampaignSubscriptionCategory');
			$status=$campaignemail_model->getStatus($_GET['save'],$campaign_id);
			if($status){
				$campaignemail_model->swapActive($status);
			}
			else{
				$campaignemail_model->createNew($_GET['save'],$campaign_id);
			}

			$grid->js(null,$this->js()->univ()->successMessage('Save Changes'))->reload()->execute();	
		}
	}	

	function page_NewsLetterSubCampaign(){
		$campaign_id = $this->api->StickyGET('xmarketingcampaign_campaigns_id');

		// $v=$this->add('View');
		// $v->addClass('panel panel-default');
		// $v->setStyle('padding','20px');

		$campaign_newsletter_model = $this->add('xMarketingCampaign/Model_CampaignNewsLetter');
		$campaign_newsletter_model->addCondition('campaign_id',$campaign_id);
		$crud = $this->add('CRUD');
		// $crud->add('Controller_FormBeautifier');	
		if(!$crud->isEditing()){
			$crud->add_button->setIcon('ui-icon-plusthick');
		}
		
		$crud->setModel($campaign_newsletter_model);
	}

	function page_social_campaigns(){
		$campaign_id = $this->api->StickyGET('xmarketingcampaign_campaigns_id');
		
		// $v=$this->add('View');
		// $v->addClass('panel panel-default');
		// $v->setStyle('padding','20px');

		$campaign_socialpost_model = $this->add('xMarketingCampaign/Model_CampaignSocialPost');
		$campaign_socialpost_model->addCondition('campaign_id',$campaign_id);
		$crud = $this->add('CRUD');
		$crud->setModel($campaign_socialpost_model);
		if($crud->form){
			$crud->form->getElement('socialpost_id')->setEmptyText('Please Select Post to Post');
		}

		// $crud->add('Controller_FormBeautifier');
		
		if(!$crud->isEditing()){
			$crud->add_button->setIcon('ui-icon-plusthick');
		}
	}


}		