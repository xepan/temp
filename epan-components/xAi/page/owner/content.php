<?php

class page_xAi_page_owner_content extends page_xAi_page_owner_main {
	
	function init(){
		parent::init();

		$tabs = $this->app->layout->add('Tabs');
		$dimension_tab = $tabs->addTabURL('./dimensions','Deimensions');
		$dimension_tab = $tabs->addTabURL('./iblocks','IBlocks Management');
		$dimension_tab = $tabs->addTabURL('./config','iConfiguration');

	}


	function page_dimensions(){
		$crud = $this->add('CRUD');
		$crud->setModel('xAi/Dimension');
		if($grid= $crud->grid){
			$grid->addMethod('format_edit',function($g,$f){
				if($g->model['name']=="Default")
					$g->current_row_html[$f]='';
				else
					return;
			});
			
			$grid->addFormatter('edit','edit');
			$grid->addFormatter('delete','edit');
		}
	}

	function page_iblocks(){
		$crud = $this->add('CRUD');
		$crud->setModel('xAi/Model_IBlockContent');
	}

	function page_config(){
		$config = $this->add('xAi/Model_Config')->tryLoadAny();
		$form = $this->add('Form');
		$form->setModel($config);
		$form->addSubmit('Updated');
		if($form->isSubmitted()){
			$form->update();
			$form->js(null,$form->js()->univ()->successMessage('Updated'))->reload()->execute();
		}

	}
}