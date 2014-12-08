<?php

namespace xShop;

class Model_CustomFieldValue extends \Model_Table{
	public $table='xshop_custom_fields_value';

	function init(){
		parent::init();
		
		//TODO for Mutiple Epan website
		// $this->hasOne('Epan','epan_id');
		// $this->addCondition('epan_id',$this->api->current_website->id);
			
		$this->hasOne('xShop/CustomFields','customefield_id');
		
		$this->addField('name');

		// $this->add('dynamic_model/Controller_AutoCreator');
	}
}