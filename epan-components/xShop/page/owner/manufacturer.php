<?php


class page_xShop_page_owner_manufacturer extends page_xShop_page_owner_main{
	function init(){
		parent::init();

		$manufacturer_model = $this->add('xShop/Model_Manufacturer');
		$crud=$this->app->layout->add('CRUD');
		$crud->setModel($manufacturer_model);
		// $crud->add('Controller_FormBeautifier');
		
		if(!$crud->isEditing()){
			$crud->grid->addQuickSearch(array('name','mobile_no','address'));
			$crud->grid->addPaginator($ipp=50);
		}

	}
}	