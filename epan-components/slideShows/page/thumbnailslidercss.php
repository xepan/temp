<?php

class page_slideShows_page_thumbnailslidercss extends Page {
	function init(){
		parent::init();

		$css_file = getcwd().DS.'epans/'.$this->api->current_website['name'].'/slider.css';
		$css_file_orig = getcwd().DS.'epan-components/slideShows/templates/css/slider.css';
		
		if(!file_exists($css_file)){	
			$css_content_orig = file_get_contents($css_file_orig);
			// throw new \Exception($css_content_orig);
			file_put_contents($css_file, $css_content_orig);			
		}

		$css_content = file_get_contents($css_file);

		$form = $this->add('Form');
		$form->addField('text','css')->set($css_content);
		$form->addSubmit('UPDATE');

		if($form->isSubmitted()){
			$user_css = 'epans/'.$this->api->current_website['name'].'/slider.css';
			$new_css_content = $form['css'];
			file_put_contents($user_css, $new_css_content);
			$form->js(
				null,
				$form->js()->_selector('#slideShows-thumbnail-customcss-link')->attr('href',$user_css. "?".rand(1000,9999))
				)->univ()->closeDialog()->execute();
		}

	}
}