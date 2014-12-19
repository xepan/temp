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
		$this->template->trySetHtml('marked_new',$model['new']?'<span class="pull-right atk-swatch-green badge">On</span>':'<span class="pull-right atk-swatch-red badge">Off</span>');
		$this->template->trySetHtml('marked_feature',$model['feature']?'<span class="pull-right atk-swatch-green badge">On</span>':'<span class="pull-right atk-swatch-red badge">Off</span>');
		$this->template->trySetHtml('marked_latest',$model['latest']?'<span class="pull-right atk-swatch-green badge">On</span>':'<span class="pull-right atk-swatch-red badge">Off</span>');
		$this->template->trySetHtml('marked_mostview',$model['mostviewed']?'<span class="pull-right atk-swatch-green badge">On':'<span class="pull-right atk-swatch-red badge">Off</span>');
		
		//Published 
		$this->template->trySetHtml('published',$model['is_publish']?'<span class="pull-right atk-swatch-green badge">Yes</span>':'<span class="pull-right atk-swatch-red badge">No</span>');
		//Enquiry
		$this->template->trySetHtml('self',$model['enquiry_send_to_self']?'<span class="pull-right atk-swatch-green badge">On</span>':'<span class="pull-right atk-swatch-red badge">Off</span>');
		$this->template->trySetHtml('supplier123',$model['enquiry_send_to_supplier']?'<span class="pull-right atk-swatch-green badge">On</span>':'<span class="pull-right atk-swatch-red badge">Off</span>');
		$this->template->trySetHtml('manufacturer123',$model['enquiry_send_to_manufacturer']?'<span class="pull-right atk-swatch-green badge">On</span>':'<span class="pull-right atk-swatch-red badge">Off</span>');
		$this->template->trySetHtml('auto_reply',$model['product_enquiry_auto_reply']?'<span class="pull-right atk-swatch-green badge">On':'<span class="atk-swatch-red badge">Off</span>');

		// Item Display Options
		$this->template->trySetHtml('offer',$model['show_offer']?'<span class="pull-right atk-swatch-green badge">On</span>':'<span class="pull-right atk-swatch-red badge">Off</span>');
		$this->template->trySetHtml('detail',$model['show_detail']?'<span class="pull-right atk-swatch-green badge">On</span>':'<span class="pull-right atk-swatch-red badge">Off</span>');
		$this->template->trySetHtml('price',$model['show_price']?'<span class="pull-right atk-swatch-green badge">On</span>':'<span class="pull-right atk-swatch-red badge">Off</span>');
		$this->template->trySetHtml('manufacturer_detail',$model['show_manufacturer_detail']?'<span class="atk-swatch-green badge">On</span>':'<span class="atk-swatch-red badge">Off</span>');
		$this->template->trySetHtml('supplier_detail',$model['show_supplier_detail']?'<span class="atk-swatch-green badge">On</span>':'<span class="atk-swatch-red badge">Off</span>');
		
		// Item Allow Options
		$this->template->trySetHtml('attachment',$model['allow_attachment']?'<span class="pull-right atk-swatch-green badge">On</span>':'<span class="pull-right atk-swatch-red badge">Off</span>');
		$this->template->trySetHtml('enquiry',$model['allow_enquiry']?'<span class="pull-right atk-swatch-green badge">On</span>':'<span class="pull-right atk-swatch-red badge">Off</span>');
		$this->template->trySetHtml('saleable',$model['allow_saleable']?'<span class="pull-right atk-swatch-green badge">On</span>':'<span class="pull-right atk-swatch-red badge">Off</span>');
		
		//Item comment options
		$this->template->trySetHtml('comment',$model['allow_comments']?'<span class="pull-right atk-swatch-green badge">On</span>':'<span class="pull-right atk-swatch-red badge">Off</span>');
		
		//Item button and meta options
		$this->template->trySetHtml('custom_button',$model['add_custom_button']?'<span class="pull-right atk-swatch-green badge">On</span>':'<span class="pull-right atk-swatch-red badge">Off</span>');
		
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
		return array('view/xShop-productdetailview');
	}
	
}
