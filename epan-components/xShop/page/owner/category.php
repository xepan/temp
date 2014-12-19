<?php

class page_xShop_page_owner_category extends page_xShop_page_owner_main{

	function init(){
		parent::init();
		
				
		$application_id=$this->api->recall('xshop_application_id');
		//Badges 
		$bg=$this->app->layout->add('View_BadgeGroup');
		$active = $this->add('xShop/Model_Category')->getActiveCategory($application_id)->count()->getOne();
		$unactive = $this->add('xShop/Model_Category')->getUnactiveCategory($application_id)->count()->getOne();
		$v=$bg->add('View_Badge')->set(' Active Category')->setCount($active)->setCountSwatch('ink');
		if($unactive)
			$v=$bg->add('View_Badge')->set(' Unactive Category')->setCount($unactive)->setCountSwatch('red');

		$category_model = $this->add('xShop/Model_Category');
		$category_model->addCondition('application_id',$application_id);	
						
		$crud=$this->app->layout->add('CRUD');
		$crud->addClass('xshop-owner-category');
		$category_model->setOrder('id','desc');

		$crud->setModel($category_model,array('parent_id','name','order_no','is_active','meta_title','meta_description','meta_keywords','image_url','alt_text','description'),array('name','parent','order_no','is_active'));
		// $crud->add('Controller_FormBeautifier',array('params'=>array('f/addClass'=>'stacked')));
		
		if($crud->form){							
			$parent_model = $crud->form->getElement('parent_id')->getModel();
			$parent_model->title_field='category_name';
			$parent_model->addCondition('application_id',$_GET['xshop_application_id']);
			// throw new \Exception("Error Processing Request", 1);
			// $parent_model->debug();
		}

		if(!$crud->isEditing()){
			$g = $crud->grid;
			$g->addClass('panel panel-default');
			$crud->grid->addClass('mygrid');//Todo for reload of crud->grid 
			$crud->grid->js('reload')->reload();//adding trigger 
			$crud->grid->addQuickSearch(array('name','parent','order_no'));
			$crud->grid->addPaginator($ipp=100);
			// $crud->grid->addcolumn('expander','groupaccess');
			$crud->grid->addcolumn('Button','duplicate');
			// $crud->grid->add('misc/Export');
		}

		if($_GET['duplicate']){
			$this->api->stickyGET('duplicate');
			$category_model->duplicate($_GET['duplicate']);
			$crud->js(null,$crud->js()->_selector('.mygrid')->trigger('reload'))->univ()->successMessage('Duplicate Category Added Successfully')->execute();	
		}

	}

	function page_groupaccess(){
		$this->add('View_Info')->set('Not Working yet.. TODO Display ACL');	
	}

}