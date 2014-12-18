<?php

class CRUD_User extends CRUD{

	public $app_page=null;
	public $option_page=null;
	public $config_page=null;

	function init(){
		parent::init();

		if(!$this->isEditing()){
			$this->grid->addQuickSearch(array('name','username','email'));
			$this->grid->addPaginator(100);
			$this->grid->add_sno();
		}
	}

	function recursiveRender(){

		$this->addOptions();
		$this->addCustomFieldsOptions();
		$this->addAppAccessOptions();
			
		if(!$this->isEditing()){
			$this->grid->addMethod('format_delete2',function($g,$f){
				if($g->model->isDefaultSuperUser()){
					$g->current_row_html['delete']="";
				}
			});

			$this->grid->addFormatter('delete','delete2');

			$this->grid->addMethod('format_edit2',function($g,$f){
				if($g->model->isDefaultSuperUser() and !$g->model->isMe()){
					$g->current_row_html['edit']="";
				}
			});

			$this->grid->addFormatter('edit','edit2');

		}

		parent::recursiveRender();
	}

	function render(){
		if($this->isEditing()){
			if($this->form->getModel()->isDefaultSuperUser()){
				$this->form->getElement('is_active')->destroy();
				$this->form->getElement('user_management')->destroy();
				$this->form->getElement('general_settings')->destroy();
				$this->form->getElement('application_management')->destroy();
				$this->form->getElement('website_desinging')->destroy();
			}
		}
		parent::render();
	}

	function addOptions(){
		if(!$this->isEditing()){
			$op_btn = $this->grid->addButton(array('Options','icon'=>'cog'));
			$op_btn->js('click',$this->js()->univ()->frameURL('User Options',$this->option_page));
		}
	}

	function addCustomFieldsOptions(){
		if(!$this->isEditing()){
			$cust_btn = $this->grid->addButton('User Custom Fields');
			$cust_btn->setIcon('ui-icon-wrench');
			$cust_btn->js('click',$this->js()->univ()->frameURL('User Custom Fields ',$this->config_page));
		}
	}


	function addAppAccessOptions(){
		if(!$this->isEditing()){
			$this->grid->addMethod('format_app_permission',function($g,$f){
				if($g->model->isFrontEndUser() or $g->model->isDefaultSuperUser()){
					$g->current_row_html['application_permissions']="";
				}
			});

			$this->grid->addColumn('Expander,app_permission','application_permissions',array('page'=>$this->app_page,'descr'=>'App'));
		}else{
			if($this->model->isFrontEndUser()){
				$this->form->getElement('user_management')->destroy();
				$this->form->getElement('general_settings')->destroy();
				$this->form->getElement('application_management')->destroy();
				$this->form->getElement('website_desinging')->destroy();
			}
		}
	}

	
}