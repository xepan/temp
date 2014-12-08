<?php

class page_xShop_page_owner_order extends page_xShop_page_owner_main{

	function page_index(){

		$crud=$this->add('CRUD');
		$orders=$this->add('xShop/Model_Order');
		$orders->setOrder('id',true);
		$crud->setModel($orders);
		$crud->add('Controller_FormBeautifier');
		if($crud->grid){
			$crud->grid->addQuickSearch(array('member','order_id','amount','billing_address','shipping_address','order_date'));
			$crud->grid->addPaginator($ipp=50);
		}

	}
}	