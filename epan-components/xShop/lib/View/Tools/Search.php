<?php

namespace xShop;

class View_Tools_Search extends \componentBase\View_Component{
	public $html_attributes=array(); // ONLY Available in server side components
	function init(){
		parent::init();

	$search_result_subpage=$this->html_attributes['xshop_search_result_subpage'];
	if(!$search_result_subpage){
		$search_result_subpage="home";
	}

	$search_model = $this->add('xShop/Model_Product'); 	
	$form=$this->add('Form');
   	$form_field=$form->addField('line','search')->setAttr('PlaceHolder','Type Your Search String here');
   	$form_field->setModel($search_model);
	// $form->template->tryDel('button_row');

   	if($form->isSubmitted()){
   		$form->api->redirect($this->api->url(null,array('subpage'=>$search_result_subpage,'search'=>$form['search'])));
   		$this->js()->reload(array(''))->execute();
   	}

	}
	// defined in parent class
	// Template of this tool is view/namespace-ToolName.html
}