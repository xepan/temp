<?php

namespace xShop;
class Model_Application extends \Model_Table{
	var $table="xshop_application";

	function init(){
		parent::init();

		$this->hasOne('Epan','epan_id');
		$this->addCondition('epan_id',$this->api->current_website->id);

		$f=$this->addField('name')->mandatory(true)->group('a~12')->sortable(true);
		$f->icon = 'fa fa-folder~red';
		
		$this->addField('type')->enum(array('Shop','Blog'));

		$this->hasMany('xShop/Category','application_id');
		$this->hasMany('xShop/Item','application_id');
		$this->addHook('beforeDelete',$this);
		
		$this->add('dynamic_model/Controller_AutoCreator'); 
	}

	function beforeDelete($m){
		if($m->ref('xShop/Category')->count()->getOne()){
			throw $this->exception("Shop/Blog (".$m['name'].") cannot deleted, first delete its category",'Growl');
		}	

	}

}		