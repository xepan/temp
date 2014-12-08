<?php

namespace xShop;

class View_Lister_Cart extends \CompleteLister{
	
	function formatRow(){		

		$product_model=$this->add('xShop/Model_Product');
		$product_model->addCondition('sku',$this->model['item_code']);
		$product_model->tryLoadAny();
		$this->current_row['xshop_cart_lg_item_img_value'] = $product_model->ref('xShop/ProductImages')->tryLoadAny()->get('image_url');			
		$this->current_row['xshop_cart_lg_item_text_value'] = $this->model['item_code']." ".$this->model['item_name'];
		$this->current_row['xshop_cart_lg_item_price_value'] = $this->model['rate'];
		$this->current_row['xshop_cart_lg_item_qty_value'] =  $this->model['qty'];
		$this->current_row['xshop_cart_lg_item_total_value'] = $this->model['rate'] * $this->model['qty'];
		$this->current_row['xshop_cart_lg_item_remove_btn'] = " X ";
		$product_model->unLoad();
	}


	function defaultTemplate(){
		$l=$this->api->locate('addons',__NAMESPACE__, 'location');
		$this->api->pathfinder->addLocation(
			$this->api->locate('addons',__NAMESPACE__),
			array(
		  		'template'=>'templates',
		  		'css'=>'templates/css',
		  		'js'=>'templates/js'
				)
			)->setParent($l);
			return array('view/xShop-xCart-lg');		
	}

	
}