<?php
 
namespace xShop;

class View_Tools_Checkout extends \componentBase\View_Component{
	public $html_attributes=array(); // ONLY Available in server side components
	function init(){
		parent::init();

		$this->js()->_load('xShop-js');
		// if($this->html_attributes['xshop_checkout_noauth_view']=='on'){
		// }
		// throw new \Exception("Error Processing Request".$this->html_attributes['xshop_checkout_noauth_subpage_url']);
		
		if($this->html_attributes['xshop_checkout_noauth_subpage_url']=='on'){						
			if($this->html_attributes['xshop_checkout_noauth_subpage']){
			 	if(!$this->add('xShop/Controller_Auth',array('redirect_subpage'=>$this->html_attributes['xshop_checkout_noauth_subpage']))){		 			
		 			return;			
			 	} 		 		
			}else
				$this->add('View_Error')->set('Subpage Name Cannot be Empty');			
		}else{
			if(!$this->add('xShop/Controller_Auth',array('substitute_view'=>"baseElements/View_Tools_UserPanel"))){			
				return;	
			}																
		}

		$cart_items=$this->add('xShop/Model_Cart');
		$product=$this->add('xShop/Model_Product');

		$form=$this->add('Form');
		$form->addClass( 'stacked' );

		$i=1;
		$amount_fields_array=array();
		foreach ($cart_items as $ci) {		
			$product->load($ci['item_id']);
			$product_id=$form->addField('hidden','productid_'.$i,'')->set($ci['item_id']);
			$product_rate=$form->addField('hidden','productrate_'.$i,'')->set($ci['rate']);			
			$form->addSeparator( 'atk-row noborder' );
			
			$product_field=$form->addField('Readonly','product_'.$i,'Product')->set($ci['item_name']);
			
			$product_field->template->set('row_class','span6');
			$qty_field=$form->addField('line','qty_'.$i,'Qty')->set($ci['qty']);
			$qty_field->template->set('row_class','span2');
			$qty_field->addClass('numberOnly');
			$rate_field=$form->addField('Readonly','rate_'.$i,'Rate')->set($ci['rateperitem']);
			
			$rate_field->template->set('row_class','span2');
			
			$amount_field=$form->addField('line','amount_'.$i,'Amount')->set($ci['qty'] * $ci['rate']);
			$amount_field->template->set('row_class','span2');
			$amount_field->setAttr( 'disabled', 'true' )->addClass('disabled_input');	
		
			$amount_fields_array[] =$amount_field;
			$i++;
		}

		$total_field = $form->addField('line','total');
		$total_field->setAttr('disabled',true);
		$total_field->template->set('row_class','span2');

		$discount_field = $form->addField('line','discount_voucher');
		$discount_field->template->set('row_class','span2');
		
		$discount_amount_field = $form->addField('line','discount_amount');
		$discount_amount_field->setAttr('disabled',true);
		$discount_amount_field->template->set('row_class','span2');

		$net_amount_field = $form->addField('line','net_amount');
		$net_amount_field->template->set('row_class','span2');		
		$net_amount_field->setAttr('disabled',true);		
							
		$discount_field->js('change')->univ()->validateVoucher($discount_field,$form,$discount_amount_field,$total_field,$net_amount_field);
					
		$i=1;
		$initial_total = 0;
		foreach ($cart_items as $ci) {
			$qty_field = $form->getElement('qty_'.$i);
			$amount_field = $form->getElement('amount_'.$i);
			$product_rate = $form->getElement('productrate_'.$i);
			
			$qty_field->js('change')->univ()
				->calculateRow($qty_field,$product_rate,$amount_field)
				->calculateTotal($amount_fields_array,$total_field)
				->calculateNet($total_field,$net_amount_field)
				->validateVoucher($discount_field,$form,$discount_amount_field,$total_field,$net_amount_field)				
				;		
			$initial_total += ($ci['qty'] * $ci['rateperitem']);
			$i++;
		}

		$total_field->set($initial_total);
		$net_amount_field->set($initial_total);	
		
		$col=$form->add('Columns');
		$colleft=$col->addColumn(6);
		$colright=$col->addColumn(6);

		$member=$this->add('xShop/Model_MemberDetails');
		if($this->api->auth->model->id){	
			$member->addCondition('users_id',$this->api->auth->model->id);
			$member->tryLoadAny();
		}
		$form->setModel($member,array('address','landmark','city','state','country','pincode'));
		$b_a=$form->getElement('address');
		$b_a->setCaption('Billing Address')->js(true)->closest('div.atk-form-row')->appendTo($colleft);
		$b_l=$form->getElement('landmark');
		$b_l->js(true)->closest('div.atk-form-row')->appendTo($colleft);
		$b_c=$form->getElement('city');
		$b_c->js(true)->closest('div.atk-form-row')->appendTo($colleft);
		$b_s=$form->getElement('state');
		$b_s->js(true)->closest('div.atk-form-row')->appendTo($colleft);
		$b_country=$form->getElement('country');
		$b_country->js(true)->closest('div.atk-form-row')->appendTo($colleft);
		$b_p=$form->getElement('pincode');
		$b_p->js(true)->closest('div.atk-form-row')->appendTo($colleft);
		$form->addField('Checkbox','i_read',"I have Read All trems & Conditions")->validateNotNull()->js(true)->closest('div.atk-form-row')->appendTo($colleft);
		
		$s_a=$form->addField('text','shipping_address')->validateNotNull(true);
		$s_a->js(true)->closest('div.atk-form-row')->appendTo($colright);
		$s_l=$form->addField('line','s_landmark','Landmark')->validateNotNull(true);
		$s_l->js(true)->closest('div.atk-form-row')->appendTo($colright);
		$s_c=$form->addField('line','s_city','City')->validateNotNull(true);
		$s_c->js(true)->closest('div.atk-form-row')->appendTo($colright);
		$s_s=$form->addField('line','s_state','State')->validateNotNull(true);
		$s_s->js(true)->closest('div.atk-form-row')->appendTo($colright);
		$s_country=$form->addField('line','s_country','Country')->validateNotNull(true);
		$s_country->js(true)->closest('div.atk-form-row')->appendTo($colright);
		$s_p=$form->addField('Number','s_pincode','Pincode')->validateNotNull(true);
		$s_p->js(true)->closest('div.atk-form-row')->appendTo($colright);		
		$shipping=$form->addButton('Copy Address');
		$shipping->js(true)->appendTo($colright);
		
		$shipping->js('click')->univ()->copyBillingAddress($b_a,$b_l,$b_c,$b_s,$b_country,$b_p,$s_a,$s_l,$s_c,$s_s,$s_country,$s_p);

		$form->addSubmit('PlaceOrder');
		
		if($form->isSubmitted()){
			$cart=$this->add('xShop/Model_Cart');		
			if(!$form['i_read'])
				$form->displayError('i_read','It is Must');

			$order=$this->add('xShop/Model_Order');
			$latest_order_id = $order->placeOrder($form->getAllFields());																													
			//Sending OrderDetail
			$cart_items->emptyCart();
			$order->sendOrderDetail($this->api->auth->model['email'],$latest_order_id);
			$form->js()->univ()->successMessage('Order Send SuccessFully');			  
			$order->processPayment();
			$this->js(null,$this->js()->univ()->successMessage('Order Placed Successfully'))->univ()->redirect($this->api->url(null,array('subpage'=>'home')))->execute();
		}
	}
	// defined in parent class
	// Template of this tool is view/namespace-ToolName.html
}