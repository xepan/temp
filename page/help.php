<?php

class page_help extends Page {
	function init(){
		parent::init();
	
		$m['created_at']='1970-01-01';

		echo (Carbon::createFromFormat("Y-m-d",$m['created_at'])->diffForHumans(Carbon::now())); 
		

	}

	// function defaultTemplate(){
	// 	return array('view/editorhelp');
	// }
}