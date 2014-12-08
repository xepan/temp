<?php

namespace xAi;

class Model_Data extends \Model_Table {
	var $table= "xai_data";
	function init(){
		parent::init();

		$this->hasOne('xAi\Session','session_id');

		$this->addField('name')->type('text');

		$this->hasMany('xAi/Information','data_id');

		// $this->add('dynamic_model/Controller_AutoCreator');
	}
}