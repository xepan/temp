<?php

namespace xShop;

class View_PrintOrder extends \View{
	function init(){
		parent::init();
		
	}  

	function setModel($model){

		$user_model = $this->add('xShop/Model_MemberDetails');
		$user_model->getAllDetail($this->api->auth->model->id);
		
		$config_model = $this->add('xShop/Model_Configuration');
		$config_model->tryLoadAny();
		$order_template = $config_model['order_detail_email_body'];
		
		// REPLACING VALUE INTO ORDER DETAIL TEMPLATES
		$order_template = str_replace("{{user_name}}", $this->api->auth->model['name'], $order_template);
		$order_template = str_replace("{{mobile_number}}", $user_model['mobile_number'], $order_template);
		$order_template = str_replace("{{billing_address}}",$model['billing_address'], $order_template);
		$order_template = str_replace("{{shipping_address}}", $model['shipping_address'], $order_template);
		$order_template = str_replace("{{email}}", $this->api->auth->model['email'], $order_template);

		$order_detail = $this->add('xShop/Model_OrderDetails')->addCondition('order_id',$model->id);
		$view=$this->add('xShop/View_OrderDetail',null,'order_detail');
		$view->setModel($order_detail);

		$order_template = str_replace("{{order_number}}", $model->id, $order_template);
		$order_template = str_replace("{{order_date}}", $model['order_date'], $order_template);
		$order_template = str_replace("{{order_mode}}", $model['payment_status'], $order_template);
		$order_template = str_replace("{{order_destination}}", $model['shipping_address'], $order_template);
		$order_template = str_replace("{{order_detail}}", $view->getHtml(true), $order_template);
		
		$this->template->SetHtml('order_address',$order_template);
		parent::setModel($model);

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
		return array('view/xShop-Order');
	}
	
}
