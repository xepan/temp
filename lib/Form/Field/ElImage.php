<?php

class Form_Field_ElImage extends Form_Field_Line{
	function init(){
		parent::init();

		$btn=$this->afterField()->add('Button')->set("Select ".$this->short_name);
		$btn->js('click')->_load('elimage')->univ()->myelimage($this);
	}
}