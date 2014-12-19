<?php

class page_xShop_page_owner_item extends page_xShop_page_owner_main{

	function init(){
		parent::init();
		
		$application_id=$this->api->recall('xshop_application_id');

		$item_model = $this->add('xShop/Model_Item');
		
		$bg=$this->app->layout->add('View_BadgeGroup');
		$v=$bg->add('View_Badge')->set(' Total Item ')->setCount($item_model->getItemCount($application_id))->setCountSwatch('ink');
		$v=$bg->add('View_Badge')->set(' Publish Item ')->setCount($item_model->getPublishCount($application_id))->setCountSwatch('green');
		$v=$bg->add('View_Badge')->set(' Unpublish Item ')->setCount($this->add('xShop/Model_Item')->getUnpublishCount($application_id))->setCountSwatch('red');
		
		$model = $this->add('xShop/Model_Item');
		$model = $model->applicationItems($application_id);

		$crud=$this->app->layout->add('CRUD',array('grid_class'=>'xShop/Grid_Item'));
		$crud->setModel($model,array('supplier_id','manufacturer_id','name','sku','is_publish','short_description','description','original_price','sale_price','rank_weight','created_at','expiry_date','allow_attachment','allow_enquiry','allow_saleable','show_offer','show_detail','show_price','show_manufacturer_detail','show_supplier_detail','new','feature','latest','mostviewed','enquiry_send_to_self','enquiry_send_to_supplier','enquiry_send_to_manufacturer','item_enquiry_auto_reply','allow_comments','comment_api','add_custom_button','custom_button_text','custom_button_url','meta_title','meta_description','tags'),array('name','sku','sale_price','is_publish'));
		// $crud->add('Controller_FormBeautifier',array('params'=>array('f/addClass'=>'stacked')));
		// if($crud->isEditing()){
		// 	$model_cat=$crud->form->getElement('categoryitem_id')->getModel();
		// 	$model_cat->title_field="category_name";
		// }

		$ref = $crud->addRef('xShop/ItemImages',array('label'=>'Images'));
		// // if($ref){
		// // 	$ref->add('Controller_FormBeautifier');
		// // }
		$ref = $crud->addRef('xShop/CustomFields',array('label'=>'Custome Fields'));
		// // if($ref){
		// // 	$ref->add('Controller_FormBeautifier');
		// // }
		$ref = $crud->addRef('xShop/Attachments',array('label'=>'Attachment'));
		// if($ref){
		// 	$ref->add('Controller_FormBeautifier');
		// }
		
		// $c_col->add('View_Info')->set('Category');
		// $crud1=$c_col->add('CRUD');
		// $m=$this->add('xShop/Model_Categoryitem');
		// $crud1->setModel($m);
	}

	function page_categories(){

		$application_id=$this->api->recall('xshop_application_id');
		
		$item_id = $this->api->stickyGET('xshop_items_id');
		$item = $this->add('xShop/Model_Item')->load($_GET['xshop_items_id']);
		$grid=$this->add('Grid');
		$app_cat_model=$this->add('xShop/Model_Category',array('table_alias'=>'mc'));
		$app_cat_model = $app_cat_model->getActiveCategory($application_id);

		// selector form
		$form = $this->add('Form');
		$app_cat_field = $form->addField('hidden','app_cat')->set(json_encode($item->getAssociatedCategories()));
		$form->addSubmit('Update');
		
		$app_cat_model->addExpression('status')->set(function($m,$q)use($item_id){
			$category_prod_model=$m->add('xShop/Model_CategoryItem',array('table_alias'=>'c'));
			$category_prod_model->addCondition('category_id',$q->getField('id'));
			$category_prod_model->addCondition('item_id',$item_id);
			$category_prod_model->addCondition('is_associate',true);
			return $category_prod_model->count();
		})->type('boolean');

		
		// $grid->addSelectable('category_name');
		// $grid->addColumn('Button','save','Swap Select');
		// $grid->addColumn('Button','cancle');
		// $save->js('click',$grid->reload())->univ()->successMessage('Save Changes Successfully',array())->execute();

		$grid->setModel($app_cat_model,array('category_name','application','status'));
		$grid->addSelectable($app_cat_field);

		if($form->isSubmitted()){
			// $cat_item = $this->add('xShop/Model_CategoryItem');
			// $cat_item->addCondition('item_id')
			$item->ref('xShop/CategoryItem')->deleteAll();

			$cat_item_model = $this->add('xShop/Model_CategoryItem');
			$selected_categories = json_decode($form['app_cat'],true);

			foreach ($selected_categories as $cat_id) {
				$cat_item_model->createNew($cat_id,$_GET['xshop_items_id']);
			}
			
			// Update Search String
			$item_model=$this->add('xShop/Model_Item');
			$item_model->load($item_id);
			$item_model->updateSearchString($_GET['xshop_items_id']);

			$form->js(null,$this->js()->univ()->successMessage('Updated'))->reload()->execute();
		}

		$grid->addQuickSearch(array('category_name'));
		$grid->addPaginator($ipp=20);
	}

	function page_details(){
		$pro_id=$this->api->stickyGET('xshop_items_id');	
		$item_model = $this->add('xShop/Model_Item');
		$item_model->getItem($pro_id);
		$product_view = $this->add('xShop/View_ProductDetail');
		$product_view->setModel($item_model);
	}

}