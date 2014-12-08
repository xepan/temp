<?php

namespace xShop;

class View_Tools_xCart extends \componentBase\View_Component{
	public $html_attributes=array(); // ONLY Available in server side components
	function init(){
		parent::init();
		$this->addClass('xshop-cart');

		// value passing game via body using attr from add to cart button 
		$this->js('reload')->reload(array('product_id'=>$this->js()->_selector('body')->attr('xshop_add_product_id')));		
		//add Cart model work as a session
		$cart_model=$this->add('xShop/Model_Cart');
		$product_model=$this->add('xShop/Model_Product');

		if($_GET['product_id'] AND $_GET['product_id'] != 'undefined'){
																			
			// from simple add to cart button on product lister
				// $this->add('View')->set(rand(1000,9999). ' ' . $_GET['product_id'] );
				$product_model->load($_GET['product_id']);			
				$cart_model->addToCart($_GET['product_id'],$product_model['sku'],$product_model['name'],1,$product_model['sale_price'], null,null);						
		}

		//Get Total amount and Total Item	
		$total_amount=$cart_model->getTotalAmount();
		$total_item=$cart_model->getItemCount();


		switch ($this->html_attributes['xshop_cart_layout']) {
				case 1:
					$this->template->tryDel('xshop_cart_with_item_price_btn');
					$this->template->tryDel('xshop_cart_lg');
					$this->template->Set('xshop_item_count',$total_item);
					$url="index.php?subpage=home";
					if($this->html_attributes['xshop_ic_cartdetail_page']){
						$url="index.php?subpage=".$this->html_attributes['xshop_ic_cartdetail_page'];
					}
					$this->template->trySet('xshop_cart_detail_page',$url);
					break;
				case 2:

					$this->template->tryDel('xshop_cart_with_item_no');
					$this->template->tryDel('xshop_cart_lg');				
						
						if($this->html_attributes['xshop_ipb_cart_empty_btn']=='false'){
							$this->template->tryDel('xshop_ipb_cart_empty_btn');
						}else{
							$empty_btn=$this->add('Button',null,'xshop_ipb_cart_empty')->set('empty')->addClass('btn btn-default btn-block');
							if($empty_btn->isClicked()){
								$cart_model->emptyCart();								
								$this->api->js()->univ()->redirect('/')->execute();
							}
						}

						if($this->html_attributes['xshop_ipb_cart_checkout_btn']=='false'){
							$this->template->tryDel('xshop_ipb_cart_checkout_btn');
						}else{
							$checkout_btn=$this->add('Button',null,'xshop_ipb_cart_checkout_btn1')->set('Check out')->addClass('btn btn-default btn-block');
							if($checkout_btn->isClicked()){
							// $this->js()->univ()->alert("khk")->execute();
								$this->api->js()->univ()->redirect(null,array('subpage'=>$this->html_attributes['xshop_ipb_checkout_page']))->execute();
							}
						}

						if($this->html_attributes['xshop_ipb_cart_viewcart_btn']=='false'){
							$this->template->tryDel('xshop_ipb_cart_viewcart_btn');
						}else{
							$view_btn=$this->add('Button',null,'xshop_ipb_cart_viewcart_btn1')->set('View Cart')->addClass('btn btn-default btn-block');
							if($view_btn->isClicked()){
							// $this->js()->univ()->alert("khk")->execute();
								$this->api->js()->univ()->redirect(null,array('subpage'=>$this->html_attributes['xshop_ipb_cart_detail_page']))->execute();
							}
						}

			
						if($this->html_attributes['xshop_ipb_cart_items']=='false'){
							$this->template->tryDel('xshop_ipb_cart_items');
						}else {
							$this->template->Set('xshop_item_count',$total_item);
						}
						
						if($this->html_attributes['xshop_ipb_cart_price']=='false'){
							$this->template->tryDel('xshop_ipb_cart_price');
						}else
							$this->template->Set('xshop_cart_total_price',$total_amount);

						if($this->html_attributes['xshop_ipb_cart_detail_page']){
							$this->template->trySet('xshop_ipb_cart_detail_page',$this->html_attributes['xshop_ipb_cart_detail_page']);
							}else
								$this->template->tryDel('xshop_ipb_cart_detail_page');
					
					break;
				case 3:
					$this->template->tryDel('xshop_cart_with_item_no');
					$this->template->tryDel('xshop_cart_with_item_price_btn');											
					
					if($total_item <= 0){
						$this->add('View_Error')->set('Cart is Empty');
					}else{
						foreach ($cart_model as $junk) {
							$ci_view=$this->add('xShop/View_CartItem',array('new'=>$cart_model['id']),'xshop_cart_lg');
							$ci_view->setModel($cart_model);
						}
						$this->add('View',null,'xshop_cart_proceed')->set('Proceed')->setElement('a')->setAttr('href','index.php?subpage='.$this->html_attributes['xshop_cartdetail_checkout_subpage']);						
					}	
					break;	
				default:
					$this->template->tryDel('xshop_cart_with_item_no');
					$this->template->tryDel('xshop_cart_with_item_price_btn');
					$this->template->tryDel('xshop_cart_lg');
					$this->add('View_Error')->set('Please Select Cart Layout First');
					break;
			}

		//loading custom CSS file	
		$cart_css = 'epans/'.$this->api->current_website['name'].'/xshopcart.css';
		$this->api->template->appendHTML('js_include','<link id="xshop-cart-customcss-link" type="text/css" href="'.$cart_css.'" rel="stylesheet" />'."\n");		
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
			return array('view/xShop-xCart');		
	}	
}

