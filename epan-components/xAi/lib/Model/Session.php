<?php


namespace xAi;

class Model_Session extends \Model_Table{
	public $table ='xai_session';
	
	function init(){
		parent::init();

		$this->addField('name')->defaultValue(session_id());
		$this->addField('type')->defaultValue('website');
		$this->addField('is_goal_achieved')->type('boolean')->defaultValue(false);

		$this->addField('created_at')->type('datetime')->defaultValue(date('Y-m-d H:i:s'));
		$this->addField('updated_at')->type('datetime')->defaultValue(date('Y-m-d H:i:s'));

		$this->hasMany('xAi/Data','session_id');
		$this->hasMany('xAi/Information','session_id');

		$this->addHook('beforeSave',$this);

		// $this->add('dynamic_model/Controller_AutoCreator');
	}

	function beforeSave(){
		$this['updated_at'] = date('Y-m-d H:i:s');
	}

}