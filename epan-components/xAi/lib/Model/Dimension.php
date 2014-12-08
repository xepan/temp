<?php

namespace xAi;

class Model_Dimension extends \Model_Table{
	public $table='xai_dimension';

	function init(){
		parent::init();

		$this->hasOne('Epan','epan_id');
		$this->addCondition('epan_id',$this->api->current_website->id);

		$this->addField('name')->caption('Dimension');

		$this->hasMany('xAi/Model_IBlockContent','dimension_id');

		// $this->add('dynamic_model/Controller_AutoCreator');
	}

}