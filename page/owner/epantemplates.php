<?php

class page_owner_epantemplates extends page_base_owner {
	function init(){
		parent::init();

		$this->app->layout->template->trySetHtml('page_title','<i class="fa fa-clipboard"></i> '.  strtoupper($this->api->current_website['name']) . " :: Templates <small> Templates for your xEpan </small>" );

		if($_GET['edit_template']){
			$this->api->redirect($this->api->url('/',array('edit_template'=>$_GET['edit_template'])));
		}

		$crud = $this->app->layout->add('CRUD');
		$template_model = $this->add('Model_EpanTemplates');
		$crud->setModel($template_model,array('name','css'),array('name','is_current'));

		if(!$crud->isEditing()){
			$g=$crud->grid;
			$g->addMethod('format_disableDefaultDelete',function($grid,$field){
				if($grid->model['name']=="default"){
					$grid->current_row_html[$field]='';
				}
			});

			$g->addFormatter('delete','disableDefaultDelete');
			$g->addColumn('Button','edit_template');
			$g->addPaginator(100);
			$g->addQuickSearch(array('name'));
			$g->add_sno();
		}	
		
		// $crud->add('Controller_FormBeautifier');

	}
}