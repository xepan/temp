<?php

namespace xShop;

class View_Tools_Product extends \componentBase\View_Component{
	function init(){
		parent::init();
			
		//for grid column width
		$this->api->js()->_load('xShop-js');
		$this->api->stickyGET('search');
		$this->api->stickyGET('category_id');

		$cg_id=$this->html_attributes['xshop_product_categorygroup_id']?$this->html_attributes['xshop_product_categorygroup_id']:0;		
		if(!$cg_id){
			$this->add('View_Error')->set('Please Select category Group');
			return;
		}
			
		//Item Colunm Width 
		switch ($this->html_attributes['xshop-grid-column']) {
			case '1':
				$column_width = "col-sm-12 col-lg-12 col-md-12";	
				break;
			case '2':
				$column_width = "col-sm-6 col-lg-6 col-md-6";	
				break;
			case '3':
				$column_width = "col-sm-4 col-lg-4 col-md-4";	
				break;
			case '4':
				$column_width = "col-sm-3 col-lg-3 col-md-3";	
				break;
			case '6':
				$column_width = "col-sm-2 col-lg-2 col-md-2";
				break;
			case '12':
				$column_width = "col-sm-1 col-lg-1 col-md-1";	
				break;
			
			default:
				$column_width = "col-sm-3 col-lg-3 col-md-3";
				break;
		}

		if($this->html_attributes['xshop_productlayout'] == 'xShop-productlist'){
			$column_width = "col-sm-12 col-lg-12 col-md-12";
		}

		$product_lister_view=$this->add('xShop/View_Lister_Product',
								array('xshop_product_display_layout'=>$this->html_attributes['xshop_productlayout'],
										'xshop_product_grid_column'=>$column_width,
										'xshop_product_topbar'=>$this->html_attributes['xshop_product_topbar'],
										'xshop_product_categorygroup_id'=>$this->html_attributes['xshop_product_categorygroup_id']?$this->html_attributes['xshop_product_categorygroup_id']:0,
										'fancy_box_on'=>$this->html_attributes['xshop_product_fancy_box'],										
										'item_detail_url'=>$this->html_attributes['xshop_product_hover_detail_page'],										
										'item_detail_onclick'=>$this->html_attributes['xshop_product_hover'],
										'item_short_description'=>$this->html_attributes['xshop_item_short_description'],
										'xshop_product_detail_on_image_click'=>$this->html_attributes['xshop_product_detail_on_image_click']										
										));

		$product_model=$this->add('xShop/Model_Product');
		$product_model->addCondition('is_publish',true);
		$p_type=$this->html_attributes['xshop_producttype'];
		// Selection of Product according to options 
		// if $p_type is null
		// then default value All
		if($p_type and $p_type !='all')
			$product_model->addcondition($p_type,true);
		//todo select product according to category group id
		// Product Model according to category grooup
		$p_join=$product_model->leftJoin('xshop_category_product.product_id','id');
		$cp_join=$p_join->leftJoin('xshop_categories','category_id');
		$p_join->addField('category_id');
		$cp_join->hasOne('xShop/CategoryGroup','categorygroup_id');
		$product_model->addCondition('categorygroup_id',$cg_id);
		//end of product model accordiing to category group

		//Category Wise Product Loading
		if($_GET['category_id']){
			$product_model->addCondition('category_id',$_GET['category_id']);
		}
		//end of Category Wise Product Loading
		
		//Search Filter				
		if($search=$_GET['search']){		
			$product_model->addExpression('Relevance')->set('MATCH(search_string) AGAINST ("'.$search.'" IN BOOLEAN MODE)');
			$product_model->addCondition('Relevance','>',0);
			// throw new \Exception($product_model['Relevance']);
	 		$product_model->setOrder('Relevance','Desc');
		}
		//end Search Filter				

		if($product_model->count()->getOne() != 0)
			$product_lister_view->template->del('no_record_found');		
		
		$product_model->_dsql()->group('product_id'); // Multiple category association shows multiple times product so .. grouped
		$product_model->setOrder('created_at','desc');
		
		$product_lister_view->setModel($product_model);
		
		//Add Painator to Product List	
		$paginator = $product_lister_view->add('Paginator');
		$paginator->ipp($this->html_attributes['xshop_product_paginator']?:12);
		//end of Add Painator to Product List	

		//loading custom CSS file	
		$product_css = 'epans/'.$this->api->current_website['name'].'/xshopcustom.css';
		$this->api->template->appendHTML('js_include','<link id="xshop-product-customcss-link" type="text/css" href="'.$product_css.'" rel="stylesheet" />'."\n");
		//end of loading custom CSS file	

	}

	// defined in parent class
	// Template of this tool is view/namespace-ToolName.html

}