<?php


class page_xAi_page_owner_data_metadata extends Page {
	function init(){
		parent::init();
		$crud = $this->add('CRUD');
		$crud->setModel('xAi/MetaData');

		if($grid = $crud->grid){
			
			$grid->addMethod('format_edit',function($g,$f){
				if($g->model['name']=="ALWAYS")
					$g->current_row_html[$f]='';
				else
					return;
			});
			
			$grid->addFormatter('edit','edit');
			$grid->addFormatter('delete','edit');

		}
	}
}