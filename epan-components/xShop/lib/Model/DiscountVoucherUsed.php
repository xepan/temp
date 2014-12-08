<?php

namespace xShop;

class Model_DiscountVoucherUsed extends \Model_Table{
	public $table='xshop_discount_vouchers_used';

	function init(){
		parent::init();
		
		$this->hasOne('Epan','epan_id');
		$this->addCondition('epan_id',$this->api->current_website->id);	
		$this->hasOne('xShop/DiscountVoucher','discountvoucher_id');
		$this->hasOne('xShop/MemberDetails','member_id');	

		// $this->add('dynamic_model/Controller_AutoCreator');
	}

	// function createNew($discountvoucher_id){
	// 	if(!$this->loaded())
	// 		throw new \Exception("DiscountVoucherUsed not loaded");
			
	// 	$this['discountvoucher_id']=$discountvoucher_id;
	// 	$this['member_id']=$this->api->xecommauth->model->id;
	// 	$this->save();
		
	// }
}

