<?php

namespace xShop;

class View_MemberOrder extends \View{
	
function init(){
	parent::init();

		if($_GET['print']){			
			$this->js()->univ()->newWindow($this->api->url("xShop/page_printorder",array('order_id'=>$_GET['print'],'cut_page'=>1,'subpage'=>'xshop-junk')),null,'height=689,width=1246,scrollbar=1')->execute();
		}  
		
		$order=$this->add('xShop/Model_Order');
		$order->getAllOrder($this->api->auth->model->id);
		$grid=$this->add('Grid');
		$order->_dsql()->order('id','desc');
		$grid->setModel($order);

		$grid->addColumn('button','print');
		$grid->addPaginator(10);
		
	}  
}
