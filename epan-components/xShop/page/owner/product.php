<?php

class page_xShop_page_owner_product extends page_xShop_page_owner_main{

	function page_index(){
		// parent::init();
				
		$bv = $this->add('View_BackEndView',array('cols_widths'=>array(12)));
		$product_model = $this->add('xShop/Model_Product');
		$bv->addToTopBar('View')->setHTML('Total Product - '.$product_model->getAllProductCount())->addClass('label label-primary');
		$bv->addToTopBar('View')->setHTML('Publish Product - '.$product_model->getPublishCount())->addClass('label label-success');
		$bv->addToTopBar('View')->setHTML('Unpublish Product - '.$this->add('xShop/Model_Product')->getUnpublishCount())->addClass('label label-warning');
		
		$col=$this->add('Columns');
		$p_col=$col->addColumn(12);
		$model = $p_col->add('xShop/Model_Product');
		$crud=$p_col->add('CRUD');
		$crud->setModel($model,array('supplier_id','manufacturer_id','name','sku','is_publish','short_description','description','original_price','sale_price','rank_weight','created_at','expiry_date','allow_attachment','allow_enquiry','allow_saleable','show_offer','show_detail','show_price','show_manufacturer_detail','show_supplier_detail','new','feature','latest','mostviewed','enquiry_send_to_self','enquiry_send_to_supplier','enquiry_send_to_manufacturer','product_enquiry_auto_reply','allow_comments','comment_api','add_custom_button','custom_button_text','custom_button_url','meta_title','meta_description','tags'),array('name','sku','sale_price'));
		$crud->add('Controller_FormBeautifier',array('params'=>array('f/addClass'=>'stacked')));
		// if($crud->isEditing()){
		// 	$model_cat=$crud->form->getElement('categoryproduct_id')->getModel();
		// 	$model_cat->title_field="category_name";
		// }
				
		if($crud->grid){
			$crud->grid->addColumn('expander','details');
			$crud->grid->addColumn('expander','categories');
			$crud->grid->addQuickSearch(array('sku','name','sale_price'));
			$crud->grid->addPaginator($ipp=50);
		}

		$ref = $crud->addRef('xShop/ProductImages',array('label'=>'Images'));
		if($ref){
			$ref->add('Controller_FormBeautifier');
		}
		$ref = $crud->addRef('xShop/CustomFields',array('label'=>'Custome Fields'));
		if($ref){
			$ref->add('Controller_FormBeautifier');
		}
		$ref = $crud->addRef('xShop/Attachments',array('label'=>'Attachment'));
		if($ref){
			$ref->add('Controller_FormBeautifier');
		}
		
		// $c_col->add('View_Info')->set('Category');
		// $crud1=$c_col->add('CRUD');
		// $m=$this->add('xShop/Model_CategoryProduct');
		// $crud1->setModel($m);
	}

	function page_categories(){

		$pro_id=$this->api->stickyGET('xshop_products_id');			
		
		$grid=$this->add('Grid');
		$cat_model=$this->add('xShop/Model_Category',array('table_alias'=>'mc'));
		$cat_model->addCondition('is_active',true);

		$cat_model->addExpression('child_count')->set(function($m,$q){
			$temp = $this->add('xShop/Model_Category',array('table_alias'=>'pc'));
			$temp->addCondition('parent_id',$q->getField('id'));
			return $temp->count();
		});
		$cat_model->addCondition('child_count',0);
		// $cat_model->_dsql()->having(
	 //        	$cat_model->_dsql()->orExpr()
	 //            	->where('child_count',0)
	 //            	->where('parent_id','<>',0)
	 //            	);

		$cat_model->addExpression('category_group')->set(function($m,$q){
			return $m->refSQL('categorygroup_id')->fieldQuery('name');
		});
		
		$cat_model->addExpression('status')->set(function($m,$q)use($pro_id){
			$category_prod_model=$m->add('xShop/Model_CategoryProduct',array('table_alias'=>'c'));
			$category_prod_model->addCondition('category_id',$q->getField('id'));
			$category_prod_model->addCondition('product_id',$pro_id);
			return $category_prod_model->count();
		})->type('boolean');

		
		$grid->setModel($cat_model,array('category_name','category_group','status'));
		// $grid->addSelectable('category_name');
		$grid->addColumn('Button','save','Swap Select');
		// $grid->addColumn('Button','cancle');
		// $save->js('click',$grid->reload())->univ()->successMessage('Save Changes Successfully',array())->execute();

		if($_GET['save']){
			
			$catpro_model=$this->add('xShop/Model_CategoryProduct');
			$status=$catpro_model->getStatus($_GET['save'],$pro_id);
			if($status){
				//if(categoryproduct model has record)
				//to unactive karo loaded record
				$catpro_model->swapActive($status);
			}
			else{
				//if categoryproduct model has no record then create new entry			
				$catpro_model->createNew($_GET['save'],$pro_id);
			}
			
			$product_model=$this->add('xShop/Model_Product');
			$product_model->load($pro_id);
			$product_model->updateSearchString($pro_id);

			$grid->js(null,$this->js()->univ()->successMessage('Save Changes'))->reload()->execute();		
		}

		$grid->addQuickSearch(array('category_name'));
		$grid->addPaginator($ipp=20);
	}

	function page_details(){
		$pro_id=$this->api->stickyGET('xshop_products_id');	
		$product_model = $this->add('xShop/Model_Product');
		$product_model->getProduct($pro_id);
		$product_view = $this->add('xShop/View_ProductDetail');
		$product_view->setModel($product_model);	
	}

}