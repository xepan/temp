<?php

namespace xShop;
class Model_CategoryGroup extends \Model_Table{
	var $table="xshop_categorygroup";

	function init(){
		parent::init();

		//TODO for Mutiple Epan website
		$this->hasOne('Epan','epan_id');
		$this->addCondition('epan_id',$this->api->current_website->id);
		$f=$this->addField('name')->caption('Category Group Name')->mandatory(true)->group('a~12')->sortable(true);
		$f->icon = 'fa fa-folder~red';
		//Todo for category model with self loop of parent category
		$this->hasMany('xShop/Category','categorygroup_id');
		// $this->add('dynamic_model/Controller_AutoCreator'); 
		$this->addHook('beforeDelete',$this);
	}

	function beforeDelete($m){
		if($m->ref('xShop/Category')->count()->getOne()){
			$m->api->js(true)->univ()->errorMessage("Category Group(".$m['name'].") cannot deleted, first delete its category")->execute();
		}	

	}

}		