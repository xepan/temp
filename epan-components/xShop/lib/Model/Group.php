<?php
namespace xShop;

class Model_Group extends \Model_Table {
	var $table= "xshop_group";
	function init(){
		parent::init();

		//TODO for Mutiple Epan website
		$this->hasOne('Epan','epan_id');
		$this->addCondition('epan_id',$this->api->current_website->id);
		// TODO GROUP ACCESS of Category and other feature
		//$this->add('dynamic_model/Controller_AutoCreator');
	}
}
