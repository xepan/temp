<?php

class page_owner_epantemplates extends page_base_owner {
	function init(){
		parent::init();

		$this->add( 'H3' )->setHTML('<i class="fa fa-clipboard"></i> '.  strtoupper($this->api->current_website['name']) . " :: Templates <small> Templates for your xEpan </small>" );

		if($_GET['edit_template']){
			$this->api->redirect($this->api->url('/',array('edit_template'=>$_GET['edit_template'])));
		}

		$crud = $this->add('CRUD');
		$crud->setModel($this->api->current_website->ref('EpanTemplates'),array('name','css'),array('name','is_current'));

		if($g=$crud->grid){
			$g->addColumn('Button','edit_template');
		}

		$crud->add('Controller_FormBeautifier');

	}
}