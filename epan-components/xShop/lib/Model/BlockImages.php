<?php

namespace xShop;

class Model_BlockImages extends \Model_Table {
	var $table= "xshop_blockimages";
	function init(){
		parent::init();
		
		$this->hasOne('xShop/AddBlock','block_id');
		$f = $this->addField('image_url')->mandatory(true)->display(array('form'=>'ElImage'))->mandatory(true)->group('a~8');
		$f->icon = "glyphicon glyphicon-picture~red";
		$f = $this->addField('link')->group('a~4')->hint('Redirect URL');
		$f->icon = "fa fa-link~blue";
		$f = $this->addField('alt_text')->group('b~6')->hint('Image ALT attribute Value');
		$f = $this->addField('title')->group('b~6')->hint('Image TITLE attribute Value');

		// $this->addExpression('block_name')->set(function($m,$q){
		// 	return $m->refSQL('block_id')->fieldQuery('name');
		// });
		
		// $this->add('dynamic_model/Controller_AutoCreator');		
	}
}

