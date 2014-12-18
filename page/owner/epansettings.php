<?php

class page_owner_epansettings extends page_base_owner {
	public $tabs;

	function init(){
		parent::init();
		if(!$this->api->auth->model->isGeneralSettingsAllowed())
			$this->api->redirect('owner/not-allowed');
	}

	function page_index(){
		$this->app->layout->template->trySetHTML('page_title',"<i class='fa fa-cogs' ></i> ".strtoupper($this->api->current_website["name"])." General Settings <small>Your basic company details and outgoing email settings </small>");
		$this->tabs = $tabs = $this->app->layout->add('Tabs');
		$epan_info = $tabs->addTab('Information');
		
		$epan_info_form = $epan_info->add('Form');
		$epan_info_form->setModel($this->api->current_website,array('category_id','company_name','contact_person_name','mobile_no','email_id','address','city','state','country','keywords','description'));
		$epan_info_form->addSubmit('Update');
		$epan_info_form->add('Controller_FormBeautifier');
		
		if($epan_info_form->isSubmitted()){
			$epan_info_form->update();
			$epan_info_form->js()->univ()->successMessage('Information Updated')->execute();
		}

		$email_tab = $tabs->addTab('Email Settings');
		$email_form = $email_tab->add('Form');
		$email_form->setModel($this->api->current_website,array('email_transport','encryption','email_host','email_port','email_username','email_password','email_reply_to','email_reply_to_name','from_email','from_name','sender_email','sender_name','smtp_auto_reconnect','email_threshold','return_path'));
		$email_form->addSubmit('Update');

		// Add Placeholder values
		
		$email_form->getElement('email_transport');
		$email_form->getElement('encryption');
		$email_form->getElement('email_host')->setAttr('placeholder','i.e. ssl://mail.domain.com');
		$email_form->getElement('email_port')->setAttr('placeholder','465');
		$email_form->getElement('email_username')->setAttr('placeholder','your email id');
		$email_form->getElement('email_password')->setAttr('placeholder','your email password');
		$email_form->getElement('email_reply_to')->setAttr('placeholder','i.e. info@domain.com');
		$email_form->getElement('email_reply_to_name')->setAttr('placeholder','Your Name');
		$email_form->getElement('from_email')->setAttr('placeholder','Your email id');
		$email_form->getElement('from_name')->setAttr('placeholder','Your Name');
		$email_form->getElement('sender_email')->setAttr('placeholder','Sender Email ID');
		$email_form->getElement('sender_name')->setAttr('placeholder','Sender Name');
		$email_form->getElement('return_path')->setAttr('placeholder','Return Path');
		$email_form->getElement('smtp_auto_reconnect')->setAttr('placeholder','SMTP Auto Reconnect');
		$email_form->getElement('email_threshold')->setAttr('placeholder','Maximum Emails allowed to send per hour for Mass Emaling');
		$email_form->add('Controller_FormBeautifier');

		if($email_form->isSubmitted()){
			$email_form->update();
			$email_form->js()->univ()->successMessage('Information Updated')->execute();
		}
		
	}
}