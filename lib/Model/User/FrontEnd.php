<?php

class Model_User_FrontEnd extends Model_User_Active{
	function init(){
		parent::init();
		$this->addCondition('type','>=',80);
	}
}