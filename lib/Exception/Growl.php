<?php

class Exception_Growl extends BaseException{
	function init(){
		parent::init();
		$this->api->js()->univ()->errorMessage($this->getMessage())->execute();
	}
}