<?php
  
namespace xShop;

class View_CartItem extends \View{
	public $cart_item=array();

	function init(){
		parent::init();
		
		$this->addClass('xshop-cartdetail');
		// $this->addClass('xshop-cart');
		
	}
	function setModel($model){

		$this->template->Set('item_code',$model['item_code']);
		$this->template->Set('item_name',$model['item_name']);		
		$this->template->Set('item_id',$model['item_id']);

		$pimg_model=$this->add('xShop/Model_ProductImages');
		$pimg_model->getImageUrl($model['item_id']);

		$this->template->Set('xshop_item_image',$pimg_model['image_url']);
		$this->template->Set('id',$model['id']);

		$form=$this->add('Form',null,'qty',array('form_horizontal'));
		$q_f=$form->addField('Number','qty')->set($model['qty'])->addClass('cart-spinner');
		$q_f->setAttr('size',1);
		$q_f->js(true)->spinner(array('min'=>1));
		
		$r_f=$form->addField('line','rate')->set($model['rate']);
		$r_f->setAttr( 'disabled', 'true' )->addClass('disabled_input');
		
		$r_f_hidden=$form->addField('hidden','rateperitem')->set($model['rateperitem']);

		$this->api->js()->_load( 'xShop-js' );
		$q_f->js( 'change' )->univ()->calculateRate($q_f,$r_f_hidden,$r_f);

		$btn_submit=$form->add('View')->addClass('btn btn-warning btn-sm')->addStyle(array('margin-top'=>'25px','margin-left'=>'5px'))->set('Update');
		$btn_submit->js('click')->submit();
		
		// $btn_remove=$form->add('Button')->addClass('btn btn-danger')->addStyle(array('margin-top'=>'25px','margin-left'=>'5px'))->set('Remove');
		// if($btn_remove->isClicked()){
		// 	$this->api->stickyGet('btn_id');
		// 	$form->js()->reload();
		// 	throw new \Exception("Error Processing Request"."BTN is Clicked");		
		// }
		if($form->isSubmitted()){
			$all_cart_item_model = $this->add('xShop/Model_Cart');
			foreach ($all_cart_item_model as $item) {
				$all_cart_item_model->load($model['id']);
				$all_cart_item_model->updateCart($model['id'],$form['qty']);
				// $item['qty']=$form['qty'];		
				$form->js()->univ()->successMessage('Cart Update Successfully')->execute();					
			}
		}	

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
		//   		'css'=>'templates/css',
		//   		'js'=>'templates/js'
		// 		)
		// 	)->setParent($l);

		return array('view/xShop-CartItem');	
	}
}	