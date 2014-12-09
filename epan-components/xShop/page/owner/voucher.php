<?php

class page_xShop_page_owner_voucher extends page_xShop_page_owner_main{

	function page_index(){

		$crud=$this->app->layout->add('CRUD');
		$crud->setModel('xShop/DiscountVoucher');
		// $crud->add('Controller_FormBeautifier');
		if(!$crud->isEditing()){
			$g=$crud->grid;
			$g->addPaginator(15);
			$g->addQuickSearch(array('name'));

		}
	}
}