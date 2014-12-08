<?php

namespace xAi;

class Model_Information extends \Model_Table {
	var $table= "xai_information";
	
	function init(){
		parent::init();

		$this->hasOne('xAi/Session','session_id');
		$this->hasOne('xAi/Data','data_id');
		$this->hasOne('xAi/MetaInformation','meta_information_id');

		$this->addExpression('name')->set(function($m,$q){
			return $m->refSQL('meta_information_id')->fieldQuery('name');
		});

		$this->addField('value');
		$this->addField('weight')->defaultValue(1);

		// $this->add('dynamic_model/Controller_AutoCreator');
	}
}