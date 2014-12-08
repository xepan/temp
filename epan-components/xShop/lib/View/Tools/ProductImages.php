<?php

namespace xShop;

class View_Tools_ProductImages extends \componentBase\View_Component{
	public $html_attributes=array(); // ONLY Available in server side components
	function init(){
		parent::init();

		if(!$_GET['xshop_item_id']){
			$this->add('View_Error')->set('Item id not Define');
			return;
		}
		$product=$this->add('xShop/Model_Product');
		$product->load($_GET['xshop_item_id']);
		$images_lister = $this->add('xShop/View_Lister_ProductImages');			
		$images_lister->setModel($this->add('xShop/Model_ProductImages')->addCondition('product_id',$_GET['xshop_item_id']));	
	}

}