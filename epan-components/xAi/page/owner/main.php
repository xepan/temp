<?php

class page_xAi_page_owner_main extends page_componentBase_page_owner_main {
		
	function page_index(){

		$btn = $this->toolbar->addButton('Reset');
		$btn->js('click',$this->js()->univ()->frameURL('Reset',$this->api->url('./reset')));

		$tabs = $this->add('Tabs');
		$hr_tab = $tabs->addTabURL('xAi_page_owner_analytics_live','Live');
		$hr_tab = $tabs->addTabURL('xAi_page_owner_analytics_dashboard','Analytics');
		$hr_tab = $tabs->addTabURL('xAi_page_owner_data_management','Data Management');
		$hr_tab = $tabs->addTabURL('xAi_page_owner_information_management','Information Management');
		$hr_tab = $tabs->addTabURL('xAi_page_owner_analytics_graphmanager','Visual Management');
		$hr_tab = $tabs->addTabURL('xAi_page_owner_content','iContent Management');
		$hr_tab = $tabs->addTabURL('./Knowledge','Knowledge Management');
		// $hr_tab = $tabs->addTabURL('xAi_page_owner_hr','HR Management');

	}


	function page_knowledge(){
		$this->add('View_Error')->set('This process is not configurable right now, you will get information via email when customization will be available');
		$this->add('View_Info')->setHTML('<a href="http://www.xepan.org?subpage=bog&xshop_item_id=3" target="xepan_blog">CLICK HERE </a> to visit What\'s in V 2.0 to understand what level of Ai we are trying to put in here');
	}

	function page_reset(){
		$this->add('H1')->setHTML('<font color="red">Careful, All Your own logic/information will be lost</font><small>Reset to company default, including Graphs, Informations, And IBlock Analysis</small>');

		$btn = $this->add('Button')->set('Yap!, Go a head, I do have backup, in case!');

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