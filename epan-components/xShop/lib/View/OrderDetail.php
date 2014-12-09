<?php

namespace xShop;

class View_OrderDetail extends \CompleteLister{
	public $sno=1;
	function init(){
		parent::init();

	}

	function formatRow(){
		$this->current_row['sno']=$this->sno;
		$this->sno++;
	}

	function setModel($model){
		parent::setModel($model);
		$order= $model->ref('order_id');
		$this->template->set('gross_amount',$order['amount']);
		// $this->template->set('discount_voucher',$order['discount_voucher']);
		$this->template->set('discount_voucher_amount',$order['discount_voucher_amount']);
		$this->template->set('net_amount',$order['net_amount']);
	}

	function defaultTemplate(){

		$this->app->pathfinder->base_location->addRelativeLocation(
		    'epan-components/'.__NAMESPACE__, array(
		        'php'=>'lib',
		        'template'=>'templates',
		        'css'=>'templates/css',
		        'js'=>'templates/js',
		    )
		);
		
		// $l=$this->api->locate('addons',__NAMESPACE__, 'location');
		// $this->api->pathfinder->addLocation(
		// 	$this->api->locate('addons',__NAMESPACE__),
		// 	array(
		//   		'template'=>'templates',
		//   		'css'=>'templates/css'
		// 		)
		// 	)->setParent($l);
		return array('view/xShop-orderDetail');
	}

}