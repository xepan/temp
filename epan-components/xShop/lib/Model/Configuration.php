<?php

namespace xShop;

class Model_Configuration extends \Model_Table {
	var $table= "xshop_configuration";
	function init(){
		parent::init();

		//TODO for Mutiple Epan website
		$this->hasOne('Epan','epan_id');
		$this->addCondition('epan_id',$this->api->current_website->id);
		
		$f = $this->addField('subject')->caption('Auto Reply Mail Subject')->group('a~12~<i class="fa fa-envelope"></i> Item Enquiry Auto Reply Email');		
		$f->icon = "glyphicon glyphicon-send~blue";  
		$f = $this->addField('message')->type('text')->display(array('form'=>'RichText'))->caption('Auto Reply Mail Message')->group('a~11~dl');		
		$f->icon = "glyphicon glyphicon-send~blue";  
		
		$f = $this->addField('disqus_code')->type('text')->caption('Place the Disqus code')->PlaceHolder('Place your Disqus Code here..')->hint('Place your Discus code here')->group('x~12~<i class="fa fa-comments"></i> Item Comment System Config'); 		
		$f->icon = "fa fa-comment~blue";
			
		$f = $this->addField('add_custom_button')->type('boolean')->hint('Add Custom Button on All Product at Product Detail')->group('b~2~<i class="fa fa-cog"></i> Item Custom Button Options');
		$f->icon = "fa fa-exclamation~blue";
		$f = $this->addField('custom_button_text')->hint('Add Custom Button Text')->group('b~4');
		$f->icon = "fa fa-pencil~blue";
		$f = $this->addField('custom_button_url')->hint('Add Custom Button Redirect Url')->placeHolder('page Url like : registration, home etc.')->group('b~6');
		$f->icon = "fa fa-link~blue";
			
		$f = $this->addField('order_detail_email_subject')->group('c~12~<i class="fa fa-envelope"></i> Order Detail ( Bill ) Email');
		$f->icon = "glyphicon glyphicon-send~blue";  
		$f = $this->addField('order_detail_email_body')->type('text')->display(array('form'=>'RichText'))->caption('Order Detail Email Body')->hint('Order Bill Email Body : this Bill send to member who placed order')->group('c~11');
		$f->icon = "glyphicon glyphicon-send~blue";  

		// TODO GROUP ACCESS of Category and other feature
		// $this->add('dynamic_model/Controller_AutoCreator');
	}
	
}
