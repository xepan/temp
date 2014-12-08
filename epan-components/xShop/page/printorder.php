<?php

class page_xShop_page_printorder extends Page{

	function init(){
		parent::init();

		
		$order=$this->add('xShop/Model_Order')->load($_GET['order_id']);
		$print=$this->add('xShop/View_PrintOrder');
		$print->setModel($order);

		
	}
}
	
