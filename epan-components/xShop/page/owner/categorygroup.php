<?php

class page_xShop_page_owner_categorygroup extends page_xShop_page_owner_main{

	function init(){
		parent::init();

		$catgroup_model=$this->add('xShop/Model_CategoryGroup');	
		$cat_model=$this->add('xShop/Model_Category');	
		
		//Tobar and options	
		$bv = $this->app->layout->add('View_BackEndView',array('cols_widths'=>array(12)));
		$total_category = $cat_model->count();
		$active_category = $cat_model->addCondition('is_active',true)->count();
		$unactive_category = $this->add('xShop/Model_Category')->addCondition('is_active',false)->count();

		$bv->addToTopBar('View')->setHTML('Total Category - '.$total_category)->addClass('label label-primary');			
		$bv->addToTopBar('View')->setHTML('Active Category - '.$active_category)->addClass('label label-success ');
		$bv->addToTopBar('View')->setHTML('Unactive Category - '.$unactive_category)->addClass('label label-warning');
			
		$catgroup_model->setOrder('name','asc');
		$crud=$bv->add('CRUD');
		$crud->setModel($catgroup_model,array('name'));

		// $crud->add('Controller_FormBeautifier',array('params'=>array('f/addClass'=>'stacked')));

		if(!$crud->isEditing()){			
			$crud->grid->add_sno();
			$crud->grid->addMethod('format_category',function($g,$f){
				$g->current_row_html[$f]=$g->model->ref('xShop/Category')->count();
			});
			$crud->grid->addMethod('format_active',function($g,$f){
				$g->current_row_html[$f]=$g->model->ref('xShop/Category')->addCondition('is_active',true)->count();
			});
			$crud->grid->addMethod('format_unactive',function($g,$f){
				$g->current_row_html[$f]=$g->model->ref('xShop/Category')->addCondition('is_active',false)->count();
			});
			$crud->grid->addcolumn('category','TotalCategory');
			$crud->grid->addcolumn('active','ActiveCategory');
			$crud->grid->addcolumn('unactive','UnactiveCategory');
			$crud->grid->addcolumn('expander','category','Categories');
		}
	}

}	