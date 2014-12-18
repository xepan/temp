<?php
class page_owner_users extends page_base_owner {

	function init(){
		parent::init();

		if(!$this->api->auth->model->isUserManagementAllowed())
			$this->api->redirect('owner/not-allowed');
	}

	function page_index(){

		$this->app->layout->template->trySetHTML('page_title',"<i class='fa fa-users'></i> User Management <small>Manage your website / applications registered users</small>");
		
		//User Badges
		$bg=$this->app->layout->add('View_BadgeGroup');
		$data=$this->add('Model_Users')->count()->getOne();
		$v=$bg->add('View_Badge')->set('Total Users')->setCount($data)->setCountSwatch('ink');
		
		$data=$this->add('Model_Users')->addCondition('type',50)->count()->getOne();
		if($data!= 0)
			$v=$bg->add('View_Badge')->set('Total Front End Users')->setCount($data)->setCountSwatch('ink');
		
		$data=$this->add('Model_Users')->addCondition('type',80)->count()->getOne();
		if($data!= 0)
			$v=$bg->add('View_Badge')->set('Total Back End Users')->setCount($data)->setCountSwatch('ink');
		
		$data=$this->add('Model_Users')->addCondition('type',100)->count()->getOne();
		if($data!= 0)
			$v=$bg->add('View_Badge')->set('Total Super End Users')->setCount($data)->setCountSwatch('ink');
		
		$data=$this->add('Model_Users')->addCondition('is_active',false)->count()->getOne();
		if($data!= 0)
			$v=$bg->add('View_Badge')->set('Total Un-active Users')->setCount($data)->setCountSwatch('red');

		//end of Badges

		$crud=$this->app->layout->add('CRUD_User',array('option_page'=>$this->api->url('./options'),'config_page'=>$this->api->url('./customfieldconfig'),'app_page'=>$this->api->url('./xyz')));

		$usr=$this->add('Model_Users');
		$usr->addCondition('epan_id',$this->api->current_website->id);
		$usr->getElement('epan')->destroy();
		$crud->setModel($usr);

	}

	function page_xyz(){
		$this->api->stickyGET('users_id');
		$user = $this->add('Model_Users')->load($_GET['users_id']);

		$install_app = $this->add('Model_InstalledComponents');
		$grid = $this->add('Grid');
		$grid->setModel($install_app,array('component'));
		
		$form = $this->add('Form');
		$allowed_app = $form->addField('hidden','allowedapp')->set(json_encode($user->getAllowedApp()));
		$form->addSubmit('Update');


		$grid->addSelectable($allowed_app);

		if($form->isSubmitted()){
			$user->ref('UserAppAccess')->_dsql()->set('is_allowed',0)->update();
			$selected_apps = json_decode($form['allowedapp'],true);

			foreach ($selected_apps as $app_id) {
				$user->allowApp($app_id);
			}
			$form->js()->univ()->successMessage('Updated')->execute();
		}
	}

	function page_options(){
		$form = $this->add('Form');
		$form->addClass('stacked');
		$form->setModel($this->api->current_website,array('is_frontent_regiatrstion_allowed','user_activation','user_registration_email_subject','user_registration_email_message_body'));
		$form->addSubmit('Update');
		$form->add('Controller_FormBeautifier');
		if($form->isSubmitted()){
			$form->update();
			$form->js(null,$form->js()->univ()->successMessage('Options Updated'))->univ()->closeDialog()->execute();
		}
	}

	function page_emailconfig(){
		$form = $this->add('Form');
		$form->addClass('stacked');
		$form->setModel($this->api->current_website,array('user_registration_email_subject','user_registration_email_message_body'));
		$form->addSubmit('save');
		if($form->isSubmitted()){
			$form->update();
			$form->js(null,$form->js()->univ()->successMessage('Email Config Updated'))->univ()->closeDialog()->execute();
		}
	}

	function page_customfieldconfig(){
		$usercustomfield_model = $this->add('Model_UserCustomFields');
		$crud = $this->add('CRUD');
		$crud->setModel($usercustomfield_model);
		// $crud->add('Controller_FormBeautifier');


	}
}
