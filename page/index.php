<?php

class page_index extends Page {
	function init(){
		parent::init();

		if($this->api->edit_mode){
			if($this->api->edit_template){
				// Remove div tag arrounf page template and to remove top-page class of the div to avoid repetation
				$this->template->loadTemplateFromString('<?$Content?>');
				$this->api->template->set('edit_template','true');
				// $this->js()->_load('edit_template');
			}
			$this->api->add('editingToolbar/View_FrontToolBar',null,'editor');
		}
		
		if(!$this->api->edit_template)
			$this->setModel($this->api->current_page);
			
	}
	function setModel($page_model){
		$this->api->template->trySet('page_title',$page_model['title']);
		$this->api->template->trySet('keywords',$page_model['keywords']);
		$this->api->template->trySet('description',$page_model['description']);
		$this->api->template->trySet('style',$page_model->ref('template_id')->get('body_attributes').'; '.$page_model['body_attributes']);

		try{
			$this->api->exec_plugins('content-fetched',$page_model);
			$this->template->setHTML('Content',$page_model['content']);
			$this->api->exec_plugins('webpage-page-loaded',$page_model);
		}catch(Exception_StopInit $e){

		}
		parent::setModel($page_model);
	}


	function render(){


		if($this->api->edit_mode){
			/**
			 * Main Live Editor JavaScript File handling All Editor based working
			 */
			$this->js()->_load('epan_live_edit');

			$css=array(
					// 'templates/css/compact.css',
					'templates/css/epan_live.css',
					// Popline
					'templates/js/popline/css/normalize.css',
					'templates/js/popline/themes/default.css',
					'templates/font-awesome/css/font-awesome.min.css',
					'templates/js/google-fonts/fontselect.css',
					'elfinder/css/elfinder.min.css',
					'elfinder/css/theme.css',
				);

			foreach ($css as $css_file) {
				$this->api->template->appendHTML('js_include','<link type="text/css" href="'.$css_file.'" rel="stylesheet" />'."\n");
			}

			$scripts =array(
					'templates/js/shortcut.js',
					'templates/js/popline/build/jquery.popline.min.js',
					'templates/js/google-fonts/jquery.fontselect.js',
					'elfinder/js/elfinder.full.js',
					'elfinder/js/jquery.dialogelfinder.js'
				);

			foreach ($scripts as $script_file) {
				$this->api->template->appendHTML('js_include','<script src="'.$script_file.'"></script>'."\n");
			}
	
		}

		$theme_css = 'epans/'.$this->api->current_website['name'].'/theme.css';
		if(file_exists(getcwd().DS.$theme_css)){
			$this->api->template->appendHTML('js_include','<link id="xepan-theme-css-link" type="text/css" href="'.$theme_css.'" rel="stylesheet" />'."\n");
		}

		$user_css = 'epans/'.$this->api->current_website['name'].'/mystyles.css';
		if(file_exists(getcwd().DS.$user_css)){
			$this->api->template->appendHTML('js_include','<link id="xepan-mystyles-css-link" type="text/css" href="'.$user_css.'" rel="stylesheet" />'."\n");
		}

		parent::render();
	
	}
}
