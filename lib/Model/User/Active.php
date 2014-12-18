<?php

class Model_User_Active extends Model_Users{
	function init(){
		parent::init();
		$this->addCondition('is_active',true);
	}
}