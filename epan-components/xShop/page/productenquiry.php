<?php

class page_xShop_page_productenquiry extends Page{
	function init(){
		parent::init();
	
		//for access enquiry form from xenquiry and subscription	
		// if($_GET['xshop_enquiry_form_id'])
		// 	$this->add('xEnquiryNSubscription/View_EnquiryForm',array('data_options'=>$_GET['xshop_enquiry_form_id']));

		$enquiry_form=$this->add('Form');
		$enquiry_form->addField('line','name');
		$enquiry_form->addField('line','email_id');
		$enquiry_form->addField('line','contact_no');
		$enquiry_form->addField('text','message');
		$enquiry_form->addSubmit('send');

		if($enquiry_form->Submitted()){
			// $p=$this->add('xShop/Model_Product');
			// $p->sendMail();
		}
	}
}