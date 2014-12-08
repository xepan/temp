<?php

class Form_Field_RichText extends Form_Field_Text{
	public $rand_class=null;
	function init(){
		parent::init();
		
		// $this->js(true)->_load('elrte/js/elrte.min')->_load('rte');
		// $this->setFieldHint('Use Save Icon of this window to save the form contents');
		$this->addClass('tinymce');
		$this->owner->js()->_selector('*')->on('pagehide',$this->js()->alert('hi')->tinymce()->remove()->_enclose());
	}

	function render(){
		
		$this->js(true)->_load('tinymce/tinymce.min')->_load('tinymce/jquery.tinymce.min')->_load('tinymce/xepan1.tinymce');
		// $this->js(true)->_load('tinymce/tinymce.min')->_load('tinymce/jquery.tinymce.min')->tinymce();
		$this->js(true)->univ()->xtinymce($this);

		// $this->js(true)->univ()->createRTE(array(
		// 									'toolbar'=>'maxi',
		// 									'cssfiles'=>  array('templates/default/css/epan.css')
		// 									)
		// 						);
		parent::render();
	}
}