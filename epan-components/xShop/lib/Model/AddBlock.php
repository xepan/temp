<?php

namespace xShop;

class Model_AddBlock extends \Model_Table{
	public $table='xshop_addblock';

	function init(){
		parent::init();

		$this->hasOne('Epan','epan_id');
		$this->addCondition('epan_id',$this->api->current_website->id);	
		
		$f = $this->addField('name')->caption('Block Name')->mandatory(true);
		$f->icon = "fa fa-circle~red";
		$this->hasMany('xShop/BlockImages','block_id');
		$this->addHook('beforeDelete',$this);
		// $this->add('dynamic_model/Controller_AutoCreator');
	}

	function beforeDelete($m){
		$m->ref('xShop/BlockImages')->deleteAll();	
	}

}