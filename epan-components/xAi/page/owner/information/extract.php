<?php


class page_xAi_page_owner_information_extract extends Page {

	function page_index(){

		$data_to_monitor_model = $this->add('xAi/Model_MetaData');
		$data_to_monitor_model->addCondition('action','<>',array(-1,0));

		$grid = $this->add('Grid');
		$grid->setModel($data_to_monitor_model);

		$grid->addColumn('expander','informations');

	}

	function page_informations(){
		$this->api->stickyGET('xai_meta_data_id');
		$this->add('View_Info')->setHtml('The Current Value Of This Data is available in variable $current_data_value<br/>Always set $result variable as value in last');

		$information_extractor_model = $this->add('xAi/Model_InformationExtractor');
		$information_extractor_model->addCondition('meta_data_id',$_GET['xai_meta_data_id']);
		$information_extractor_model->setOrder('order');
		// $information_extractor_model->tryLoadAny();

		if($this->add('xAi/Model_MetaData')->load($_GET['xai_meta_data_id'])->get('name')!='ALWAYS'){
			$information_extractor_model->getElement('mark_triggering_information')->system(true);
		}

		// echo $information_extractor_model['name'];

		$crud = $this->add('CRUD');
		$crud->setModel($information_extractor_model);

	}

}