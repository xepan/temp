<?php
namespace xShop;

class Model_CategoryProduct extends \Model_Table{
	var $table="xshop_category_product";
	var $table_alias = 'catpro';

	function init(){
		parent::init();
		
		$this->hasOne('xShop/Category','category_id')->defaultValue('Null');
		$this->hasOne('xShop/Product','product_id')->defaultValue('Null');
		
		$this->addField('is_associate')->type('boolean');
			
		// $this->add('dynamic_model/Controller_AutoCreator'); 
	}

	function createNew($cat_id,$pro_id){
		$this['category_id']=$cat_id;		
		$this['product_id']=$pro_id;		
		// $this['is_associate']=true;		
		$this->save();

	}

	function swapActive($status){
		if($status)
			$this->delete();
	}

	function getStatus($cat_id,$pro_id){
		$this->addCondition('category_id',$cat_id);
		$this->addCondition('product_id',$pro_id);
		$this->tryLoadAny();
		if($this->count()->getOne()){
			return true;
		}
		else
			return false;

	}

}	