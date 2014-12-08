<?php

namespace xShop;

class View_Tools_ProductDetail extends \componentBase\View_Component{
	public $html_attributes=array(); // ONLY Available in server side components
	function init(){
		parent::init();
		

		$this->js(true)->_load('jquery-elevatezoom');
		$this->api->stickyGET('xshop_item_id');
		$config_model=$this->add('xShop/Model_Configuration');
		$product=$this->add('xShop/Model_Product');
		$manu_join = $product->leftJoin('xshop_manufacturer','manufacturer_id');	
		$manu_join->addField('manufacturer_name','name');
		$manu_join->addField('manufacturer_office_address','office_address');
		$manu_join->addField('manufacturer_email_id','email_id');
		$manu_join->addField('manufacturer_mobile_no','mobile_no');
		$manu_join->addField('manufacturer_logo_url','logo_url');
		$manu_join->addField('manufacturer_city','city');
		$manu_join->addField('manufacturer_state','state');
		$manu_join->addField('manufacturer_country','country');
		$manu_join->addField('manufacturer_zip_code','zip_code');
		$manu_join->addField('manufacturer_phone_no','phone_no');
		$manu_join->addField('manufacturer_description','description');

		$supp_join = $product->leftJoin('xShop_supplier','supplier_id');		
		$s_name=$supp_join->addField('supplier_name','name');
		$supp_join->addField('supplier_email_id','email_id');
		$supp_join->addField('supplier_office_address','office_address');
		$supp_join->addField('supplier_phone_no','phone_no');
		$supp_join->addField('supplier_mobile_no','mobile_no');
		$supp_join->addField('supplier_address','address');
		$supp_join->addField('supplier_zip_code','zip_code');
		$supp_join->addField('supplier_city','city');
		$supp_join->addField('supplier_state','state');
		$supp_join->addField('supplier_country','country');
		$supp_join->addField('supplier_description','description')->allowHtml(true);

		if($product['show_attachment']){
			$attachment_model=$this->add('xShop/Model_Attachments');
			$attachment_model->addCondition('product_id',$_GET['xshop_item_id']);
			$attachment_model->tryLoadAny();
			$this->template->set('attachment_url',$attachment_model['attachment_url']);
			$this->template->set('attachment_name',$attachment_model['name']);
		}else
			$this->template->tryDel('xshop_product_attachment');	
		// throw new \Exception("Error Processing Request".$attachment_model['attachment_url']);
		
		// $this->setModel($product_attachment_model);
		
		//Live Edit of Product Detail (server site live edit )
		if( $this->api->edit_mode == true ){		
				$this->js(true)->_load('xshopContentUpdate');
		}else{ 
			$this->template->tryDel('xshop_product_detail_live_edit_start');
			$this->template->tryDel('xshop_product_detail_live_edit_end');
		}
		//end  

		//PANEL OPTIONS
			$this->template->trySetHTML('xshop_product_detail_panel_label',$this->html_attributes['xshop_product_detail_panel_label']);
			if(!$this->html_attributes['xshop_product_detail_panel']){
				$this->template->tryDel('xshop_product_detail_panel_start');
				$this->template->tryDel('xshop_product_detail_panel_end');
			}

		//END OF PANEL OPTIONS
		if($_GET['xshop_item_id']){
			$product->load($_GET['xshop_item_id']);		
		}else{
			return;
		}

		$this->add('View',null,'description123')->setHtml($product['description']);
		//adding tag to product detail options
		$this->template->Set('xshop_product_tags',str_replace(',', " ", $product['tags']));		
		
		$details = $this->add('xShop/View_Lister_CustomFields',null,'product_custom_fields');
		$custom_field_model=$this->add('xShop/Model_CustomFields');
		$custom_field_model->addCondition('product_id',$_GET['xshop_item_id']);
		$details->setModel($custom_field_model);
							
		// do adding multiple images of a single product
		// $images = $this->add('xShop/View_Lister_ProductImages',null,'product_images');			
		// $images->setModel($this->add('xShop/Model_ProductImages')->addCondition('product_id',$_GET['xshop_item_id']));	
		
		$this->setModel($product);

		$this->api->template->trySet('page_title',$product['name']);

		if(!$product['allow_enquiry'])
			$this->template->tryDel('xshop_product_enquiry');	

		if(!$product['show_supplier_detail'])
			$this->template->tryDel('xshop_product_supplier');
		$this->template->trySet('supplier_caption',$this->html_attributes['xshop_pd_supplier_caption']?:'Supplier');

		if(!$product['show_price']){
			// throw new \Exception("Error Processing Request", 1);
			$this->template->tryDel('xshop_productdetail_price');
		}

		if(!$product['show_manufacturer_detail'])
			$this->template->tryDel('xshop_product_manufacturer');
		$this->template->trySet('manufacturer_caption',$this->html_attributes['xshop_pd_manufacturer_caption']?:'Manufacturer');
		
		if($this->html_attributes['xshop_product_detail_images']==1){
			$this->template->tryDel('xshop_product_detail_images');	
		}

		if($product['allow_comments']){	
			$config_model->tryLoadAny();
			if($product['comment_api']){
				if($config_model['disqus_code'])
					$this->template->trySetHTML('xshop_product_discus',$config_model['disqus_code']);
				else
					$this->template->trySetHTML('xshop_product_discus',"<div class='alert alert-info'>Place Your Discus Code...in Configuration</div>");			
			} 
		}

		if($product['allow_saleable']){
			$this->template->trySetHTML('aj',str_replace('"', "'", $this->js(null, $this->js()->_selector('body')->attr('xshop_add_product_id',$this->model->id))->_selector(' .xshop-cart ')->trigger('reload')->_render()));			
		}else{
			$this->current_row_html['aj']='';
			$this->template->tryDel('xshop_product_cart_btn');	
		}	
		
		$this->template->trySetHTML('supplier_description_copy',$this->model['supplier_description']);
		$this->template->trySetHTML('manufacturer_description_copy',$this->model['manufacturer_description']);

		$this->add('View',null,'send_button')->set('Send Enquiry')->addClass('btn btn-default');
		
		//add custom btn in product detail
		$config_model->tryLoadAny();
		if($config_model['add_custom_button']){
			if($this->model['add_custom_button']){
				$this->add('View',null,'xshop_product_custom_btn')->set($this->model['custom_button_text'])->addClass('btn btn-default');
				$this->template->trySetHTML('xshop_product_custom_btn_url',$this->model['custom_button_url']);
			}else{
				$this->add('View',null,'xshop_product_custom_btn')->set($config_model['custom_button_text'])->addClass('btn btn-default');
				$this->template->trySetHTML('xshop_product_custom_btn_url',$config_model['custom_button_url']);
			}		
		}
		//end of custom btn in product detail		

		$enquiry_form=$this->add('Form',null,'xshop_product_enquiry_form');
		$enquiry_form->addField('line','name');
		$enquiry_form->addField('line','contact_no');
		$enquiry_form->addField('line','email_id');
		$enquiry_form->addField('text','message');
		$enquiry_form->addSubmit('Send');
		if($enquiry_form->Submitted()){	
			$product_enq_model=$this->add('xShop/Model_ProductEnquiry');
			$epan=$this->add('Model_Epan');
			$epan->tryLoadAny();
			// throw new \Exception("Error Processing Request".$product[]);
			$product_enq_model->createNew($enquiry_form['name'],$enquiry_form['contact_no'],$enquiry_form['email_id'],$enquiry_form['message'],$product['id'],$product['sku'],$product['name']);
				
			if($product['enquiry_send_to_self']){
				$product->sendEnquiryMail($epan['email_id'],$enquiry_form['name'],$enquiry_form['contact_no'],$enquiry_form['email_id'],$enquiry_form['message'],$enquiry_form,$product['name'],$product['SKU']);
			}
				
			if($product['enquiry_send_to_supplier']){
				// $product_enq_model->createNew($enquiry_form['name'],$enquiry_form['contact_no'],$enquiry_form['email_id'],$enquiry_form['message'],$product['id'],$product['sku'],$product['name']);				
				$product->sendEnquiryMail($product['supplier_email_id'],$enquiry_form['name'],$enquiry_form['contact_no'],$enquiry_form['email_id'],$enquiry_form['message'],$enquiry_form,$product['name'],$product['sku']);
			}

			if($product['enquiry_send_to_manufacturer']){
				// $product_enq_model->createNew($enquiry_form['name'],$enquiry_form['contact_no'],$enquiry_form['email_id'],$enquiry_form['message'],$product['id'],$product['sku'],$product['name']);
				$product->sendEnquiryMail($product['manufacturer_email_id'],$enquiry_form['name'],$enquiry_form['contact_no'],$enquiry_form['email_id'],$enquiry_form['message'],$enquiry_form,$product['name'],$product['SKU']);
				// throw new \Exception($product['manufacturer_email_id']);
			}	
			
			if($product['product_enquiry_auto_reply']){
				$product->sendEnquiryMail($enquiry_form['email_id'],null,null,null,null,null,$product['name'],$product['sku'],'1');
			}

			$enquiry_form->js(true,$enquiry_form->js()->reload())->univ()->successMessage('Enquiry Form Send Success fully')->execute();
		}
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

		return array('view/xShop-ProductDetail');
	}

	// defined in parent class
	// Template of this tool is view/namespace-ToolName.html
}