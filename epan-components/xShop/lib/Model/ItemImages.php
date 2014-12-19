<?php

namespace xShop;

class Model_ItemImages extends \Model_Table {
	var $table= "xshop_item_images";
	function init(){
		parent::init();
		
		$this->hasOne('xShop/Item','item_id');
		
		$f = $this->addField('image_url')->mandatory(true)->display(array('form'=>'ElImage'))->group('a~12~<i class="glyphicon glyphicon-picture"></i> Media Management');
		$f->icon ="glyphicon glyphicon-picture~blue";
		$f = $this->addField('alt_text')->group('a~11~bl');
		$f->icon ="glyphicon glyphicon-pencil~blue";
		$f = $this->addField('title')->group('a~11~bl');
		$f->icon ="glyphicon glyphicon-pencil~blue";

		// $this->add('dynamic_model/Controller_AutoCreator');		
	}

	function getImageUrl($item_id){
		$this->addCondition('item_id',$item_id);
		$this->tryLoadAny();
		return $this;
	}
}

