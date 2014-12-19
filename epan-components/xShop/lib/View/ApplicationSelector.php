<?php

namespace xShop;

class View_ApplicationSelector extends \View{

	public $title="";
	public $title_view;
	public $applicaion_form;

	function init(){
		parent::init();
		// $this->spot='';
		$cols = $this->add('Columns');
		$this->left=$cols->addColumn(8);
		$this->right=$cols->addColumn(4);
		$this->title_view = $this->left->add('View');


	}

	function setTitle($title){
		$this->title = $title;
		return $this;
	}

	function recursiveRender(){
		$this->title_view->setHTML($this->title);

		$f = $this->applicaion_form = $this->right->add('Form');
		$af = $f->addField('DropDown','applications');
		$af->setModel('xShop/Application');
		$af->set($this->api->xshop_application_id());
		$af->afterField()->add('Button')->set(array('','icon'=>'export'))->js('click',$f->js()->submit());
		if($this->applicaion_form->isSubmitted()){
			$this->api->memorize('xshop_application_id',$f['applications']);
			$this->api->redirect($this->api->url(null));
		}

		parent::recursiveRender();
	}
}