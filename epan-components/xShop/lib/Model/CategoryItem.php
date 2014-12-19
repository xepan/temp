<?php
namespace xShop;

class Model_CategoryItem extends \Model_Table{
	var $table="xshop_category_item";
	var $table_alias = 'catitem';

	function init(){
		parent::init();
		
		$this->hasOne('xShop/Category','category_id')->defaultValue('Null');
		$this->hasOne('xShop/Item','item_id')->defaultValue('Null');
		
		$this->addField('is_associate')->type('boolean');
			
		// $this->add('dynamic_model/Controller_AutoCreator'); 
	}

	function createNew($cat_id,$item_id){
		$this['category_id']=$cat_id;		
		$this['item_id']=$item_id;
		$this['is_associate']=true;
		$this->saveAndUnload();
	}
	
	function getStatus($cat_id,$item_id){
		$this->addCondition('category_id',$cat_id);
		$this->addCondition('item_id',$item_id);
		$this->addCondition('is_associate',false);
		$this->tryLoadAny();
		return $this;
	}

}	