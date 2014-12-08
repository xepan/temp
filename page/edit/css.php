<?php

class page_edit_css extends Page {
	function init(){
		parent::init();

		$css_file = getcwd().DS.'epans/'.$this->api->current_website['name'].'/mystyles.css';
		if(!file_exists($css_file)) file_put_contents($css_file, "");
		$css_content = file_get_contents($css_file);


		$form = $this->add('Form');
		$form->addField('text','css')->set($css_content);
		$form->addSubmit('UPDATE');

		if($form->isSubmitted()){
			$user_css = 'epans/'.$this->api->current_website['name'].'/mystyles.css';

			$new_css_content = $form['css'];
			file_put_contents($css_file, $new_css_content);
			$form->js(
				null,
				$form->js()->_selector('#xepan-mystyles-css-link')->attr('href',$user_css. "?".rand(1000,9999))
				)->univ()->closeDialog()->execute();
		}
	}
}