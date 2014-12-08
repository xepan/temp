<?php

class page_xShop_page_owner_categorygroup_category extends page_xShop_page_owner_main{

	function page_index(){
		// parent::init();
		
		$categorygroup_id=$this->api->stickyGET('xshop_categorygroup_id');
		$col=$this->add('Columns');
		$d_col=$col->addcolumn(3);
		$e_col=$col->addcolumn(3);
		$t_col=$col->addcolumn(3);
		$a_col=$col->addcolumn(3);
		// $d_col->add('View_Info')->set('Disable Category');
		// $e_col->add('View_Error')->set('Empty Category');
		// $t_col->add('View_Info')->set('Top Category');
		// $a_col->add('View_Error')->set('Average no of Product');
		

		$category_model = $this->add('xShop/Model_Category');
		$category_model->addCondition('categorygroup_id',$categorygroup_id);	
						
		$crud=$this->add('CRUD');
		$crud->addClass('xshop-owner-category');
		$category_model->setOrder('id','asc');

		$crud->setModel($category_model,array('parent_id','name','order','is_active','meta_title','meta_description','meta_keywords','image_url','alt_text','description'),array('name','parent','order','is_active'));
		$crud->add('Controller_FormBeautifier',array('params'=>array('f/addClass'=>'stacked')));
		
		if($crud->form){
									
			$parent_model = $crud->form->getElement('parent_id')->getModel();
			$parent_model->title_field='category_name';
			$parent_model->addCondition('categorygroup_id',$_GET['xshop_categorygroup_id']);
			// throw new \Exception("Error Processing Request", 1);
			// $parent_model->debug();
		}

		if($g = $crud->grid){
			$g->addClass('panel panel-default');
			$g->addStyle('padding','20px');
			$crud->grid->addClass('mygrid');//Todo for reload of crud->grid 
			$crud->grid->js('reload')->reload();//adding trigger 
			$crud->grid->addQuickSearch(array('name','parent','order'));
			$crud->grid->addPaginator($ipp=30);
			// $crud->grid->addcolumn('expander','groupaccess');
			$crud->grid->addcolumn('Button','duplicate');
			// $crud->grid->add('misc/Export');
		}

		if($_GET['duplicate']){
			$this->api->stickyGET('duplicate');
			$category_model->duplicate($_GET['duplicate']);
			$crud->js(null,$crud->js()->_selector('.mygrid')->trigger('reload'))->univ()->successMessage('Category Duplicate Successfully')->execute();	
		}

	}

	function page_groupaccess(){
		$this->add('View_Info')->set('Not Working yet.. TODO Display ACL');	
	}


}