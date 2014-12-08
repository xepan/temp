<?php

class page_xShop_page_owner_update extends page_componentBase_page_update {
		
	public $git_path="https://github.com/xepan/xShop"; // Put your components git path here

	function init(){
		parent::init();
		// 
		// Code To run before update
		
		$this->update(false); // All modls will be dynamic executed in here

		$model = $this->add('xShop/Model_CategoryGroup');
		$model->add('dynamic_model/Controller_AutoCreator');
		$model->tryLoadAny();

		$model = $this->add('xShop/Model_Category');
		$model->getElement('parent_id')->destroy();
		$model->addField('parent_id');
		$model->add('dynamic_model/Controller_AutoCreator');
		$model->tryLoadAny();

		$model = $this->add('xShop/Model_Supplier');
		$model->add('dynamic_model/Controller_AutoCreator');
		$model->tryLoadAny();

		$model = $this->add('xShop/Model_Manufacturer');
		$model->add('dynamic_model/Controller_AutoCreator');
		$model->tryLoadAny();

		$model = $this->add('xShop/Model_Product');
		$model->add('dynamic_model/Controller_AutoCreator');
		$model->tryLoadAny();

		$model = $this->add('xShop/Model_CategoryProduct');
		$model->add('dynamic_model/Controller_AutoCreator');
		$model->tryLoadAny();

		$model = $this->add('xShop/Model_Attachments');
		$model->add('dynamic_model/Controller_AutoCreator');
		$model->tryLoadAny();

		$model = $this->add('xShop/Model_CustomFields');
		$model->add('dynamic_model/Controller_AutoCreator');
		$model->tryLoadAny();

		$model = $this->add('xShop/Model_CustomFieldValue');
		$model->add('dynamic_model/Controller_AutoCreator');
		$model->tryLoadAny();
		
		$model = $this->add('xShop/Model_Group');
		$model->add('dynamic_model/Controller_AutoCreator');
		$model->tryLoadAny();

		$model = $this->add('xShop/Model_Configuration');
		$model->add('dynamic_model/Controller_AutoCreator');
		$model->tryLoadAny();

		$model = $this->add('xShop/Model_ProductEnquiry');
		$model->add('dynamic_model/Controller_AutoCreator');
		$model->tryLoadAny();

		$model = $this->add('xShop/Model_ProductImages');
		$model->add('dynamic_model/Controller_AutoCreator');
		$model->tryLoadAny();

		$model = $this->add('xShop/Model_AddBlock');
		$model->add('dynamic_model/Controller_AutoCreator');
		$model->tryLoadAny();

		$model = $this->add('xShop/Model_BlockImages');
		$model->add('dynamic_model/Controller_AutoCreator');
		$model->tryLoadAny();

		$model = $this->add('xShop/Model_Configuration');
		$model->add('dynamic_model/Controller_AutoCreator');
		$model->tryLoadAny();

		$model = $this->add('xShop/Model_MemberDetails');
		$model->add('dynamic_model/Controller_AutoCreator');
		$model->tryLoadAny();

		$model = $this->add('xShop/Model_DiscountVoucher');
		$model->add('dynamic_model/Controller_AutoCreator');
		$model->tryLoadAny();
		
		$model = $this->add('xShop/Model_DiscountVoucherUsed');
		$model->add('dynamic_model/Controller_AutoCreator');
		$model->tryLoadAny();

		$model = $this->add('xShop/Model_Order');
		$model->add('dynamic_model/Controller_AutoCreator');
		$model->tryLoadAny();

		$model = $this->add('xShop/Model_OrderDetails');
		$model->add('dynamic_model/Controller_AutoCreator');
		$model->tryLoadAny();

		$model = $this->add('xShop/Model_ProductEnquiry');
		$model->add('dynamic_model/Controller_AutoCreator');
		$model->tryLoadAny();


		$this->add('View_Info')->set('Component Is SuccessFully Updated');
		// Code to run after update
	}
}