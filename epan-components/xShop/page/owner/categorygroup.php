<?php

class page_xShop_page_owner_categorygroup extends page_xShop_page_owner_main{

	function init(){
		parent::init();

		$catgroup_model=$this->add('xShop/Model_CategoryGroup');	
		$cat_model=$this->add('xShop/Model_Category');	
		
		//Tobar and options
		$bg=$this->app->layout->add('View_BadgeGroup');
		
		$total_category = $cat_model->count();
		$active_category = $cat_model->addCondition('is_active',true)->count();
		$unactive_category = $this->add('xShop/Model_Category')->addCondition('is_active',false)->count();
		
		$v=$bg->add('View_Badge')->set(' Total Category ')->setCount($total_category)->setCountSwatch('ink');
		$v=$bg->add('View_Badge')->set(' Active Category ')->setCount($active_category)->setCountSwatch('green');
		$v=$bg->add('View_Badge')->set(' Unactive Category ')->setCount($unactive_category)->setCountSwatch('red');
		
		//--------end of topbar
			
		$catgroup_model->setOrder('name','asc');
		$crud=$this->app->layout->add('CRUD');
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