<?php

namespace xAi;

class Model_MetaInformation extends \Model_Table{
	public $table ='xai_meta_information';

	function init(){
		parent::init();
		$this->hasMany('xAi/Information','meta_information_id');
		$this->addField('name');

		$this->addField('is_triggering')->type('boolean')->defaultValue(false);

		// $this->add('dynamic_model/Controller_AutoCreator');
	}
}