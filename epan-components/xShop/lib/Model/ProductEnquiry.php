<?php

namespace xShop;
class Model_ProductEnquiry extends \Model_Table{
	var $table="xshop_productenquiry";

	function init(){
		parent::init();

		//TODO for Mutiple Epan website
		$this->hasOne('Epan','epan_id');
		$this->hasOne('xShop/Product','product_id');
		$this->addCondition('epan_id',$this->api->current_website->id);
		
		$this->addField('name');
		$this->addField('contact_no');
		$this->addField('email_id');
		$this->addField('message')->type('text');
		$this->addField('product_name');
		$this->addField('product_code');
		$this->addField('created_at')->type('datetime')->defaultValue(date('Y-m-d h:i:s'));

		// $this->add('dynamic_model/Controller_AutoCreator');	
	}

	function createNew($name,$contact_no,$email_id,$message,$product_id,$product_code,$product_name){
		if($this->loaded())
			throw new \Exception("Product Enquiry Model is Loaded");
		
		$this['epan_id']=$this->api->current_website->id;
		$this['name']=$name;			
		$this['contact_no']=$contact_no;			
		$this['email_id']=$email_id;			
		$this['message']=$message;
		$this['product_id']=$product_id;
		$this['product_code']=$product_code;
		$this['product_name']=$product_name;
		$this['created_at']=date('Y-m-d h:i:s');

		$this->saveAndUnload();			
	}

}	