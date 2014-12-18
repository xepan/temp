<?php

class Model_UserAppAccess extends Model_Table{
	public $table="userappaccess";

	function init(){
		parent::init();

		$this->hasOne('Epan','epan_id');
		$this->addCondition('epan_id',$this->api->current_website->id);

		$this->hasOne('Users','user_id');
		$this->hasOne('InstalledComponents','installed_app_id');

		$this->addField('created_at')->type('datetime')->defaultValue(date('Y-m-d H:i:s'));
		$this->addField('is_allowed')->type('boolean')->defaultValue(false)->sortable(true);

		$this->add('dynamic_model/Controller_AutoCreator');
	}
}