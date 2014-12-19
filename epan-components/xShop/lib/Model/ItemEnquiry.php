<?php

namespace xShop;
class Model_ItemEnquiry extends \Model_Table{
	var $table="xshop_itemenquiry";

	function init(){
		parent::init();

		//TODO for Mutiple Epan website
		$this->hasOne('Epan','epan_id');
		$this->hasOne('xShop/Item','item_id');
		$this->addCondition('epan_id',$this->api->current_website->id);
		
		$this->addField('name');
		$this->addField('contact_no');
		$this->addField('email_id');
		$this->addField('message')->type('text');
		$this->addField('item_name');
		$this->addField('item_code');
		$this->addField('created_at')->type('datetime')->defaultValue(date('Y-m-d h:i:s'));

		// $this->add('dynamic_model/Controller_AutoCreator');	
	}

	function createNew($name,$contact_no,$email_id,$message,$item_id,$item_code,$item_name){
		if($this->loaded())
			throw new \Exception("item Enquiry Model is Loaded");
		
		$this['epan_id']=$this->api->current_website->id;
		$this['name']=$name;			
		$this['contact_no']=$contact_no;			
		$this['email_id']=$email_id;			
		$this['message']=$message;
		$this['item_id']=$item_id;
		$this['item_code']=$item_code;
		$this['item_name']=$item_name;
		$this['created_at']=date('Y-m-d h:i:s');

		$this->saveAndUnload();			
	}

}	