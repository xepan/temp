<?php

namespace xShop;

class Model_OrderDetails extends \Model_Table{
	public $table='xshop_orderDetails';

	function init(){
		parent::init();

		$this->hasOne('Epan','epan_id');
		$this->addCondition('epan_id',$this->api->current_website->id);
		$this->hasOne('xShop/Order','order_id');
		$this->hasOne('xShop/Item','item_id');

		$this->addField('qty')->type('money');
		$this->addField('unit')->type('money');
		$this->addField('rate')->type('money');
		$this->addField('amount')->type('money');


		// $this->add('dynamic_model/Controller_AutoCreator');
	}
}

