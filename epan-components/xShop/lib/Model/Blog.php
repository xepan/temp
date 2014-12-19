<?php

namespace xShop;

class Model_Blog extends Model_Application {
	function init(){
		parent::init();
		$this->addCondition('type','Blog');
	}	
}