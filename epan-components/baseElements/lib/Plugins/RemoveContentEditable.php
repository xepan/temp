<?php

namespace baseElements;


class Plugins_RemoveContentEditable extends \componentBase\Plugin {

	function init(){
		parent::init();
		$this->addHook('content-fetched',array($this,'Plugins_RemoveContentEditable'));
	}

	function Plugins_RemoveContentEditable($obj, $page){
		if(!$this->api->edit_mode){
			$page['content'] = str_replace('contenteditable="true"', 'contenteditable="false"', $page['content']);	
		}
	}
}
