<?php

namespace xShop;

class Model_Cart extends \Model{
	
	function init(){
		parent::init();
		$this->setSource('Session');

		$this->addField('item_id');
		$this->addField('item_code');
		$this->addField('item_name');
		$this->addField('qty');
		$this->addField('rate');
		$this->addField('rateperitem');
		$this->addField('custom_fields');
		
	}

	function addToCart($id,$code,$name,$qty,$rate,$custom_fields=null,$otherfield=null){			
		
		$this['item_id'] = $id;
		$this['item_code'] = $code;
		$this['item_name'] = $name;
		$this['qty'] = $qty;
		$this['rateperitem'] = $rate;
		$this['rate'] = $rate;
		$this['custom_fields'] = $custom_fields;
		$this->save();			
	}

	function getItemCount(){
		
		$item_count=0;
		foreach ($this as $junk) {
			$item_count += $junk['qty'];
		}

		return $item_count;
	}

	function getTotalAmount() { 
		$total_amount=0;
		$cart=$this->add('xShop/Model_Cart');
		foreach ($cart as $junk) {
			$total_amount += $junk['rate'];
		}

		return $total_amount;
	}

	function emptyCart(){
		 foreach ($this as $junk) {
			$this->delete();
		 }
	}

	function updateCart($id,$qty){
		if(!$this->loaded())
			throw new \Exception("Cart Model Not Loaded at update cart".$this['item_name']);		
		
		$this['qty']=$qty;
		$this['rate'] = $this['rateperitem'] * $qty;
		$this->save();
		// $this->unLoad();
		// throw new \Exception("Cart Model Loaded at update cart");
	}

	function remove($cartitem_id){
		$this->load($cartitem_id);
		$this->delete();		
	}
	
}