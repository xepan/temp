<?php


class page_xShop_page_owner_supplier extends page_xShop_page_owner_main{
	function page_index(){

		$supplier_model = $this->add('xShop/Model_Supplier');
		$crud=$this->add('CRUD');
		$crud->setModel($supplier_model);
		$crud->add('Controller_FormBeautifier');
		if($crud->grid){
			$crud->grid->addQuickSearch(array('name','mobile_no','address'));
			$crud->grid->addPaginator($ipp=50);
		}

	}
}	