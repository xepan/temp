<?php

namespace {namespace};


class {PluginName} extends \componentBase\Plugin {

	function init(){
		parent::init();
		$this->addHook('{event-hook}',array($this,'{PluginName}'));
	}

	function {PluginName}($obj{,params}){

	}
}
