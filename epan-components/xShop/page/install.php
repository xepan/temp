<?php

class page_xShop_page_install extends page_componentBase_page_install {
	function init(){
		parent::init();

		// 
		// Code To run before installing
		
		$this->install();

		// Code to run after installation
		// Loading Product CSS file 
		$css_file = getcwd().DS.'epans/'.$this->api->current_website['name'].'/xshopcustom.css';
		$css_file_orig = getcwd().DS.'epan-components/xShop/templates/css/xshopcustom.css';
		
		if(!file_exists($css_file)){
			$css_content_orig = file_get_contents($css_file_orig);
			// throw new \Exception($css_content_orig);
			file_put_contents($css_file, "$css_content_orig");			
			$this->api->template->appendHTML('js_include','<link id="xshop-product-customcss-link" type="text/css" href="'.$css_file.'" rel="stylesheet" />'."\n");
		}
		//Loading Category Css File
		$css_file = getcwd().DS.'epans/'.$this->api->current_website['name'].'/xshopcategory.css';
		$css_file_orig = getcwd().DS.'epan-components/xShop/templates/css/xshopcategory.css';
		
		if(!file_exists($css_file)){
			$css_content_orig = file_get_contents($css_file_orig);
			// throw new \Exception($css_content_orig);
			file_put_contents($css_file, "$css_content_orig");			
			$this->api->template->appendHTML('js_include','<link id="xshop-category-customcss-link" type="text/css" href="'.$css_file.'" rel="stylesheet" />'."\n");
		}

		//Loading xCart CSS File for
		$css_file = getcwd().DS.'epans/'.$this->api->current_website['name'].'/xshopcart.css';
		$css_file_orig = getcwd().DS.'epan-components/xShop/templates/css/xshopcart.css';
		
		if(!file_exists($css_file)){
			$css_content_orig = file_get_contents($css_file_orig);
			// throw new \Exception($css_content_orig);
			file_put_contents($css_file, "$css_content_orig");			
			$this->api->template->appendHTML('js_include','<link id="xshop-cart-customcss-link" type="text/css" href="'.$css_file.'" rel="stylesheet" />'."\n");
		}
		
	}
}