<?php

class page_xShop_page_owner_configuration extends page_xShop_page_owner_main{

	function page_index(){
	
		// $this->add('View_Info')->set('Product Enquiry Form Configuration');
		$config_model=$this->add('xShop/Model_Configuration');
		$form=$this->app->layout->add('Form');
		$row_count=$config_model->count('id')->getOne();
		if($row_count >= '1'){
			$config_model->addCondition('id',$row_count);
			$config_model->tryLoadAny();
		}	
		
		$form->setModel($config_model);
		$form->addSubmit('Go');
		// $form->add('Controller_FormBeautifier');
		if($form->Submitted()){
			$form->Update();
			$form->js()->univ()->successMessage('Update Successfully')->execute();	
		}

	}
}	