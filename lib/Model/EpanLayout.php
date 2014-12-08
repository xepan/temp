<?php


class Model_EpanLayout extends Model_Table {
	var $table= "epan_layout";
	
	function init(){
		parent::init();

		$this->addField('name')->mandatory();
		$this->addField('content')->type('text');
		$this->addField('is_user_created')->type('boolean')->defaultValue(true);
		//$this->add('dynamic_model/Controller_AutoCreator');
	}
}