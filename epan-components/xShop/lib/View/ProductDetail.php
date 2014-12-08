<?php

namespace xShop;

class View_ProductDetail extends \View{
	function init(){
		parent::init();
		
	}  

	function setModel($model){
		$this->template->trySetHtml('description_html',$model['description']);
		$this->template->trySetHtml('short_description_html',$model['short_description']);
		// Marked Product optins
		$this->template->trySet('marked_new',$model['new']?'On':'Off');
		$this->template->trySet('marked_feature',$model['feature']?'On':'Off');
		$this->template->trySet('marked_latest',$model['latest']?'On':'Off');
		$this->template->trySet('marked_mostview',$model['mostviewed']?'On':'Off');
		
		//Published 
		$this->template->trySet('published',$model['is_publish']?'Yes':'No');
		//Enquiry
		$this->template->trySet('self',$model['enquiry_send_to_self']?'On':'Off');
		$this->template->trySet('supplier123',$model['enquiry_send_to_supplier']?'On':'Off');
		$this->template->trySet('manufacturer123',$model['enquiry_send_to_manufacturer']?'On':'Off');
		$this->template->trySet('auto_reply',$model['product_enquiry_auto_reply']?'On':'Off');

		// Item Display Options
		$this->template->trySet('offer',$model['show_offer']?'On':'Off');
		$this->template->trySet('detail',$model['show_detail']?'On':'Off');
		$this->template->trySet('price',$model['show_price']?'On':'Off');
		$this->template->trySet('manufacturer_detail',$model['show_manufacturer_detail']?'On':'Off');
		$this->template->trySet('supplier_detail',$model['show_supplier_detail']?'On':'Off');
		
		// Item Allow Options
		$this->template->trySet('attachment',$model['allow_attachment']?'On':'Off');
		$this->template->trySet('enquiry',$model['allow_enquiry']?'On':'Off');
		$this->template->trySet('saleable',$model['allow_saleable']?'On':'Off');
		
		//Item comment options
		$this->template->trySet('comment',$model['allow_comments']?'On':'Off');
		
		//Item button and meta options
		$this->template->trySet('custom_button',$model['add_custom_button']?'On':'Off');
		
		parent::setModel($model);
	}

	function defaultTemplate(){
		$l=$this->api->locate('addons',__NAMESPACE__, 'location');
		$this->api->pathfinder->addLocation(
			$this->api->locate('addons',__NAMESPACE__),
			array(
		  		'template'=>'templates',
		  		'css'=>'templates/css'
				)
			)->setParent($l);
		return array('view/xShop-productdetailview');
	}
	
}
