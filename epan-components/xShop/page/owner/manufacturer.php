<?php


class page_xShop_page_owner_manufacturer extends page_xShop_page_owner_main{
	function init(){
		parent::init();

		//View badge
		$m = $this->add('xShop/Model_Manufacturer');
		$bg=$this->app->layout->add('View_BadgeGroup');
		$v=$bg->add('View_Badge')->set(' Total Manufacturer ')->setCount($m->count()->getOne())->setCountSwatch('ink');
		$v=$bg->add('View_Badge')->set(' Unactive Manufacturer ')->setCount($m->addCondition('is_active',false)->count()->getOne())->setCountSwatch('red');		//-----end of view badge 
		
		$manufacturer_model = $this->add('xShop/Model_Manufacturer');
		$crud=$this->app->layout->add('CRUD');
		$manufacturer_model->removeElement('epan_id');
		$crud->setModel($manufacturer_model);
		// $crud->add('Controller_FormBeautifier');
		
		if(!$crud->isEditing()){
			$crud->grid->addQuickSearch(array('name','mobile_no','address'));
			$crud->grid->addPaginator($ipp=50);

		}

	}
}	