<?php

class page_xShop_page_owner_main extends page_componentBase_page_owner_main {
	function init(){
		parent::init();

		$this->app->layout->add('xShop/View_ApplicationSelector',null,'page_title')->setTitle('<i class="fa fa-shopping-cart"></i> '.$this->component_name. '<small>Used as ( <i class="fa fa-list"></i> ) Product Listing , Blogs and ( <i class="fa fa-shopping-cart"></i> ) E-commerce kinds of Application</small>');
		// $this->app->layout->template->trySetHTML('page_title');
			
		$xshop_m = $this->app->top_menu->addMenu($this->component_name);
		$xshop_m->addItem(array('Dashboard','icon'=>'gauge-1'),'xShop_page_owner_dashboard');
		$xshop_m->addItem(array('Shops & Blogs','icon'=>'gauge-1'),'xShop_page_owner_shopsnblogs');
		$xshop_m->addItem(array('Category','icon'=>'gauge-1'),'xShop_page_owner_category');
		$xshop_m->addItem(array('Item','icon'=>'gauge-1'),'xShop_page_owner_item');
		$xshop_m->addItem(array('Manufacturer','icon'=>'gauge-1'),'xShop_page_owner_manufacturer');
		$xshop_m->addItem(array('supplier','icon'=>'gauge-1'),'xShop_page_owner_supplier');
		$xshop_m->addItem(array('E-Voucher','icon'=>'gauge-1'),'xShop_page_owner_voucher');
		$xshop_m->addItem(array('Member','icon'=>'gauge-1'),'xShop_page_owner_member');
		$xshop_m->addItem(array('Order','icon'=>'gauge-1'),'xShop_page_owner_order');
		$xshop_m->addItem(array('AddBlock','icon'=>'gauge-1'),'xShop_page_owner_addblock');
		$xshop_m->addItem(array('Configuration','icon'=>'gauge-1'),'xShop_page_owner_configuration');

		$this->api->addMethod('xshop_application_id',function($page){
			return $page->api->memorize('xshop_application_id',$page->api->recall('xshop_application_id',$page->add('xShop/Model_Application')->tryLoadAny()->get('id')));
		});

	}


	function page_config(){
		$this->add('H1')->set('Default Config Page');
	}
}