<?php

class page_xShop_page_owner_main extends page_componentBase_page_owner_main {
	function init(){
		parent::init();

		$this->app->layout->template->trySetHTML('page_title','<i class="fa fa-shopping-cart"></i> '.$this->component_name. '<small>Used as ( <i class="fa fa-list"></i> ) Product Listing , Blogs and ( <i class="fa fa-shopping-cart"></i> ) E-commerce kinds of Application</small>');
			
		$xshop_m = $this->app->top_menu->addMenu($this->component_name);
		$xshop_m->addItem(array('Dashboard','icon'=>'gauge-1'),'xShop_page_owner_dashboard');
		$xshop_m->addItem(array('Shops & Blogs','icon'=>'gauge-1'),'xShop_page_owner_shopsnblogs');
		$xshop_m->addItem(array('Category','icon'=>'gauge-1'),'xShop_page_owner_categorygroup');
		$xshop_m->addItem(array('Product','icon'=>'gauge-1'),'xShop_page_owner_product');
		$xshop_m->addItem(array('Manufacturer','icon'=>'gauge-1'),'xShop_page_owner_manufacturer');
		$xshop_m->addItem(array('supplier','icon'=>'gauge-1'),'xShop_page_owner_supplier');
		$xshop_m->addItem(array('E-Voucher','icon'=>'gauge-1'),'xShop_page_owner_voucher');
		$xshop_m->addItem(array('Member','icon'=>'gauge-1'),'xShop_page_owner_member');
		$xshop_m->addItem(array('Order','icon'=>'gauge-1'),'xShop_page_owner_order');
		$xshop_m->addItem(array('AddBlock','icon'=>'gauge-1'),'xShop_page_owner_addblock');
		$xshop_m->addItem(array('Configuration','icon'=>'gauge-1'),'xShop_page_owner_configuration');


		// $cart['item_id']=1;
		// $cart['qty']=20;
		// $cart['rate']=12345;
		// $cart->save(1);

		// $cart =$this->add('xShop/Model_Cart');
		// $cart['item_id']=1;
		// $cart['qty']=200;
		// $cart['rate']=45678;
		// $cart->save(2);

		// $cart->tryLoad(1);
		// $this->add('Text')->set($cart['item_id']);

		// foreach ($cart as $junk) {
		// 	$cart->delete();
		// }


		// $cart =$this->add('xShop/Model_Cart');
		// $g = $this->add('Grid');
		// $g->setModel($cart);
		// $g->controller->importField('id');

	}


	function page_config(){
		$this->add('H1')->set('Default Config Page');
	}
}