<?php

class page_xAi_page_owner_analytics_graphmanager extends page_xAi_page_owner_main{

	function init(){
		$this->rename('grph'); // Shorten name length for suhosin restrictions
		parent::init();

	}
	
	function page_index(){
		if($_GET['exec']){
			$this->api->stickyGET('exec');
			$visual_analytic = $this->add('xAi/Model_VisualAnalytic')->load($_GET['exec']);
			$vp =$this->add('VirtualPage');
			$vp->set(function($vp)use($visual_analytic){
				if($visual_analytic['visual_style']=='grid'){
					$vp->add('xAi/View_DynamicDataTable',array('analytic'=> $visual_analytic));
				}else{
					$vp->add('xAi/View_DynamicChart',array('analytic'=>$visual_analytic));
				}
			});

			$this->js()->univ()->frameURL($visual_analytic['name'],$vp->getURL())->execute();
		}

		$crud = $this->app->layout->add('CRUD');
		$crud->setModel('xAi/VisualAnalytic');

		if($g=$crud->grid){
			$g->addColumn('button','exec');
			$g->addColumn('expander','series');		
		}

		if($crud->isEditing()){
			$group_by = $crud->form->getElement('visual_style');
			$group_by->js(true)->univ()->bindConditionalShow(array(
				'grid'=>array('grid_group_by_meta_information_id','limit_top','grid_value'),
				'*'=>array('group_by')
					),'div .atk-form-row');

		}

	}

	function page_series(){
		$this->api->stickyGET('xai_visual_analytic_id');
		$visual_analytic = $this->add('xAi/Model_VisualAnalytic')->load($_GET['xai_visual_analytic_id']);

		if($visual_analytic['visual_style']=='grid'){
			$this->add('View_Error')->set('Grid Style Charts cannot add series');
			return;
		}

		$crud = $this->add('CRUD');

		$crud->setModel($visual_analytic->ref('xAi/VisualAnalyticSeries'));

	}

}