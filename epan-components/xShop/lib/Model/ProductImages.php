<?php

namespace xShop;

class Model_ProductImages extends \Model_Table {
	var $table= "xshop_product_images";
	function init(){
		parent::init();
		
		$this->hasOne('xShop/Product','product_id');
		
		$f = $this->addField('image_url')->mandatory(true)->display(array('form'=>'ElImage'))->group('a~12~<i class="glyphicon glyphicon-picture"></i> Media Management');
		$f->icon ="glyphicon glyphicon-picture~blue";
		$f = $this->addField('alt_text')->group('a~11~bl');
		$f->icon ="glyphicon glyphicon-pencil~blue";
		$f = $this->addField('title')->group('a~11~bl');
		$f->icon ="glyphicon glyphicon-pencil~blue";

		// $this->add('dynamic_model/Controller_AutoCreator');		
	}

	function getImageUrl($product_id){
		$this->addCondition('product_id',$product_id);
		$this->tryLoadAny();
		return $this;
	}
}

