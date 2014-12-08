<?php

class page_xAi_page_owner_update extends page_componentBase_page_update {
		
	public $git_path="https://github.com/xepan/xAi.git"; // Put your components git path here

	function init(){
		parent::init();

		// 
		// Code To run before update
		
		$this->update($dynamic_model_update=false); // All modls will be dynamic executed in here
		
		// Code to run after update
		$model_array=array('Model_Config',
							'Model_Dimension',
							'Model_IBlockContent',
							'Model_Session',
							'Model_MetaData',
							'Model_Data',
							'Model_Information',
							'Model_InformationExtractor',
							'Model_SalesExecutive',
							'Model_MetaInformation',
							'Model_VisualAnalytic',
							'Model_VisualAnalyticSeries'
			);
		foreach ($model_array as  $md) {
			$model=$this->add('xAi/'.$md);
			$model->add('dynamic_model/Controller_AutoCreator');
			$model->tryLoadAny();
		}
		$this->add('View_Info')->set('Component Updated Successfully');
	}
}