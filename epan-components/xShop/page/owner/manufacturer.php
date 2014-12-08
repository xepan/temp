<?php


class page_xShop_page_owner_manufacturer extends page_xShop_page_owner_main{
	function page_index(){

		$manufacturer_model = $this->add('xShop/Model_Manufacturer');
		$crud=$this->add('CRUD');
		$crud->setModel($manufacturer_model);
		$crud->add('Controller_FormBeautifier');
		
		if($crud->grid){
			$crud->grid->addQuickSearch(array('name','mobile_no','address'));
			$crud->grid->addPaginator($ipp=50);
		}

	}
}	