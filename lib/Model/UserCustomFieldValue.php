<?php

class Model_UserCustomFieldValue extends \Model_Table{
	public $table='user_custom_field_value';

	function init(){
		parent::init();

		$this->hasOne('Epan','epan_id');
		$this->addCondition('epan_id',$this->api->current_website->id);		
		$this->hasOne('UserCustomFields','usercustomefield_id');
		$this->hasOne('Users','users_id');

		$this->addField('name');

		// $this->add('dynamic_model/Controller_AutoCreator');
	}

	function createNew($users_id,$usercustomfield_id,$value,$othre_info=null){
		
		if($this->loaded())
			throw new \Exception("User Cutom Field Value Model Loaded");
		
		$this['epan_id'] = $this->api->current_website->id;
		$this['users_id'] = $users_id;
		$this['usercustomefield_id'] = $usercustomfield_id;
		$this['name'] = $value;
		$this->save();
		
	}

}