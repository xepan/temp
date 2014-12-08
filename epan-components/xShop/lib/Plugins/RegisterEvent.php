<?php

namespace xShop;


class Plugins_RegisterEvent extends \componentBase\Plugin {

	function init(){
		parent::init();
		$this->addHook('register-event',array($this,'Plugins_RegisterEvent'));
	}

	function Plugins_RegisterEvent($obj, $events_obj){
		$events_obj->events[] = "xshop-item-created";
		// throw new \Exception('I ma called'. print_r($events_obj->events,true));
	}
}
