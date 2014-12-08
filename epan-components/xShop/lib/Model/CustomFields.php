<?php

namespace xShop;

class Model_CustomFields extends \Model_Table{
	public $table='xshop_custom_fields';

	function init(){
		parent::init();

		$this->hasOne('xShop/Product','product_id');
		//TODO for Mutiple Epan website
		$this->hasOne('Epan','epan_id');
		$this->addCondition('epan_id',$this->api->current_website->id);
		
		$f = $this->addField('name')->mandatory(true)->group('a~6~<i class=\'fa fa-cog\'> Item Custom Fields</i>');
		$f->icon = 'fa fa-circle~red';
		$f = $this->addField('value')->group('a~6');
		$f->icon = 'fa fa-circle~blue';

		$this->hasMany('xShop/CustomFieldValue','customefield_id');

		// $this->add('dynamic_model/Controller_AutoCreator');
	}
}

