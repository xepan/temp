<?php

namespace componentBase;

abstract class Plugin extends \View{

	function init(){
		parent::init();

		$this_class = get_class($this);
		$namespace_class =explode("\\", $this_class);
		$this->namespace = $namespace_class[0];

		$this_plugin = $this->add('Model_MarketPlace')->addCondition('namespace',$this->namespace)->tryLoadAny();
		
		if(!$this_plugin->loaded())
			throw $this->exception('Cannot load Plugin')->addMoreInfo('Plugin',$this->namespace);

		if(!$this_plugin['is_system']){
			//TODO And user has not enabled this plugin then do not perform hook or brak hook
		}

	}

	function defaultTemplate(){
		return array('view/no-div');
	}
}