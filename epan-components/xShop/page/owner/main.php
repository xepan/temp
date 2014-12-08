<?php

class page_xShop_page_owner_main extends page_componentBase_page_owner_main {
	function page_index(){

		$this->h1->setHTML('<i class="fa fa-shopping-cart"></i> '.$this->component_name. '<small>Used as ( <i class="fa fa-list"></i> ) Product Listing , Blogs and ( <i class="fa fa-shopping-cart"></i> ) E-commerce kinds of Application</small>');

		$tab=$this->add('Tabs');
		$cat_tab=$tab->addTabURL('xShop/page_owner_dashboard','Dashboard');
		$cat_tab=$tab->addTabURL('xShop/page_owner_categorygroup','Category');
		$product_tab=$tab->addTabURL('xShop/page_owner_product','Product');
		$manufacturer_tab=$tab->addTabURL('xShop/page_owner_manufacturer','Manufacturer');
		$supplier_tab=$tab->addTabURL('xShop/page_owner_supplier','Supplier');
		$e_voucher_tab=$tab->addTabURL('xShop/page_owner_voucher','E-Voucher');
		$config_tab=$tab->addTabURL('xShop/page_owner_configuration','Configuration');
		$member_tab=$tab->addTabURL('xShop/page_owner_member','Member');
		$order_tab=$tab->addTabURL('xShop/page_owner_order','Order');
		$addblock_tab=$tab->addTabURL('xShop/page_owner_addblock','AddBlock');


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