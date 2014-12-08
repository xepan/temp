<?php

class page_xAi_page_owner_main extends page_componentBase_page_owner_main {
		
	function init(){
		parent::init();
		
		$this->app->layout->template->trySetHTML('page_title','<i class="fa fa-bullhorn"></i> '.$this->component_name. '<small> xAi Small</small>');
		$this->api->component_common_menu->addItem(array('Reset','icon'=>'gauge-1'),$this->api->url('xAi_page_owner_main_reset'));		
		// $btn = $this->toolbar->addButton('Reset');
		// $btn->js('click',$this->js()->univ()->frameURL('Reset',$this->api->url('./reset')));

		$xai_m=$this->app->top_menu->addMenu($this->component_name);
		$xai_m->addItem(array('Live','icon'=>'gauge-1'),'xAi_page_owner_analytics_live');
		$xai_m->addItem(array('Analytics','icon'=>'gauge-1'),'xAi_page_owner_analytics_dashboard');
		$xai_m->addItem(array('Data Management','icon'=>'gauge-1'),'xAi_page_owner_data_management');
		// Add Submenu 
		// $data_management_m = $xai_m->addMenu(array('Data Management','icon'=>'gauge-1'));
		// $data_management_m->addItem(array('Meta Data Management','icon'=>'gauge-1'),'xAi_page_owner_data_metadata');
		$xai_m->addItem(array('Information Management','icon'=>'gauge-1'),'xAi_page_owner_information_management');
		$xai_m->addItem(array('Visual Management','icon'=>'gauge-1'),'xAi_page_owner_analytics_graphmanager');
		$xai_m->addItem(array('iContent Management','icon'=>'gauge-1'),'xAi_page_owner_content');
		$xai_m->addItem(array('Knowledge Management','icon'=>'gauge-1'),$this->api->url('xAi_page_owner_main_knowledge'));	

		// $btn = $this->toolbar->addButton('Reset');
		// $btn->js('click',$this->js()->univ()->frameURL('Reset',$this->api->url('./reset')));
		// $hr_tab = $tabs->addTabURL('xAi_page_owner_hr','HR Management');
	}


	function page_knowledge(){
		$this->app->layout->add('View_Error')->set('This process is not configurable right now, you will get information via email when customization will be available');
		$this->app->layout->add('View_Info')->setHTML('<a href="http://www.xepan.org?subpage=bog&xshop_item_id=3" target="xepan_blog">CLICK HERE </a> to visit What\'s in V 2.0 to understand what level of Ai we are trying to put in here');
	}

	function page_reset(){
		$this->app->layout->add('H1')->setHTML('<font color="red">Careful, All Your own logic/information will be lost</font><small> Reset to company default, including Graphs, Informations, And IBlock Analysis</small>');

		$btn = $this->app->layout->add('Button')->set('Yap!, Go a head, I do have backup, in case!');

		if($btn->isClicked()){
			
			$truncate_table = array('xai_config', 'xai_data', 'xai_information', 'xai_informationextractor', 'xai_meta_data', 'xai_meta_information', 'xai_sales_executive', 'xai_session', 'xai_visual_analytic', 'xai_visual_analytic_series'); 
			
			foreach ($truncate_table as $junk) {
				$this->api->db->dsql()->table($junk)->truncate(); 
			}
			$file = getcwd().'/epan-components/xAi/install.sql';
			$this->api->db->dsql()->expr(file_get_contents($file))->execute();
			
			$btn->api->js()->univ()->successMessage('Reset Successfully')->execute();
		}
		

		// throw new \Exception("truncate Tables and run sql again", 1);
		

	}
}