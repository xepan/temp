<?php

namespace xAi;


class Model_Config extends \Model_Table{
	public $table = 'xai_config';

	function init(){
		parent::init();
		
		$this->hasOne('Epan','epan_id');
		$this->addCondition('epan_id',$this->api->current_website->id);

		$this->addField('is_active')->type('boolean')
			->defaultValue(true)->caption('Activate Artificial Intelligence');

		$this->addField('keep_site_constant_for_session')->type('boolean')->defaultValue(true);
		// $this->addField('send_data_frequency')->type('int')->defaultValue(6)->hint('Set 0 for immediate or no of seconds to send commulative tracking data')->system(true);

		// $this->add('dynamic_model/Controller_AutoCreator');

	}

}