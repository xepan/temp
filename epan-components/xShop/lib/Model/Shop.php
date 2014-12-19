<?php

namespace xShop;

class Model_Shop extends Model_Application {
	function init(){
		parent::init();
		$this->addCondition('type','Shop');
	}	
}